<?php

use App\Models\Distributive;
use App\Models\License;
use App\Models\Post;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// 1. Головна сторінка (передаємо останні 4 новини)
Route::get('/', function () {
    $posts = Post::where('is_published', true)
        ->orderByDesc('published_at')
        ->take(4)
        ->get();

    return view('home', compact('posts'));
});

// 1.1. Сторінка читання новини (slug)
Route::get('/news/{post:slug}', function (Post $post) {
    if (!$post->is_published) {
        abort(404);
    }
    return view('news.show', compact('post'));
})->name('news.show');

// 2. Центр завантажень
Route::get('/downloads', function () {
    $distributives = Distributive::where('is_active', true)->latest()->get();
    return view('downloads', compact('distributives'));
});

// 3. Захищений шлюз скачування через Nginx X-Accel-Redirect
Route::get('/downloads/{distributive}/download', function (Distributive $distributive) {
    if (!$distributive->is_active || !$distributive->file_path) {
        abort(404, 'Дистрибутив не знайдено або знято з публікації.');
    }

    // Перевірка: тільки для зареєстрованих
    if ($distributive->access_level === 'registered') {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Для завантаження цього файлу необхідно увійти в акаунт.']);
        }
    }

    // Перевірка: тільки для клієнтів з активною ліцензією (VIP)
    if ($distributive->access_level === 'licensed') {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Для доступу до комерційного релізу необхідна авторизація.']);
        }

        if (!Auth::user()->hasActiveLicense()) {
            abort(403, 'Доступ заборонено: у вашому обліковому записі відсутня активна ліцензія.');
        }
    }

    if (!Storage::disk('public')->exists($distributive->file_path)) {
        abort(404, 'Файл фізично відсутній на сервері.');
    }

    // --- МАГІЯ NGINX X-ACCEL-REDIRECT ---
    // PHP завершує роботу миттєво, віддаючи лише спеціальний заголовок Nginx
    $fileName = basename($distributive->file_path);
    $extension = pathinfo($distributive->file_path, PATHINFO_EXTENSION) ?: 'iso';

    return response('', 200, [
        'X-Accel-Redirect' => '/protected_files/' . $fileName,
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => 'attachment; filename="' . $distributive->name . '_v' . $distributive->version . '.' . $extension . '"',
    ]);
})->name('distributives.download');

// 4. Техпідтримка
Route::get('/support', fn () => view('support'));
Route::post('/tickets', function (Request $request) {
    $validated = $request->validate([
        'client_name' => 'required|string|max:255',
        'client_email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'priority' => 'required|in:low,medium,high,critical',
        'message' => 'required|string',
    ]);

    $ticket = Ticket::create([
        'ticket_number' => 'TCK-' . rand(10000, 99999),
        'client_name' => $validated['client_name'],
        'client_email' => $validated['client_email'],
        'subject' => $validated['subject'],
        'priority' => $validated['priority'],
        'message' => $validated['message'],
        'status' => 'new',
    ]);

    return back()->with('success', "Дякуємо! Ваш тікет #{$ticket->ticket_number} зареєстровано.");
});

// --- АВТОРИЗАЦІЯ ТА ОСОБИСТИЙ КАБІНЕТ ---

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/cabinet');
        }

        return back()->withErrors(['email' => 'Невірний email або пароль.']);
    });

    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/cabinet');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/cabinet', function () {
        $user = Auth::user();
        $licenses = License::where('user_id', $user->id)
            ->orWhere('client_email', $user->email)
            ->get();
        $tickets = Ticket::where('client_email', $user->email)->latest()->get();

        return view('cabinet', compact('user', 'licenses', 'tickets'));
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    });
});
