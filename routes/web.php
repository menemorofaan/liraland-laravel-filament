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
use Illuminate\Support\Str;

Route::get('/', function () {
    $posts = Post::where('is_published', true)->orderByDesc('published_at')->take(4)->get();
    return view('home', compact('posts'));
});

Route::get('/news/{post:slug}', function (Post $post) {
    if (!$post->is_published) abort(404);
    return view('news.show', compact('post'));
})->name('news.show');

Route::get('/downloads', function () {
    $distributives = Distributive::where('is_active', true)->latest()->get();
    return view('downloads', compact('distributives'));
});

// ПРАВИЛЬНИЙ ПОТОКОВИЙ ШЛЮЗ ІЗ ПІДТРИМКОЮ RANGE-ЗАПИТІВ (206 PARTIAL CONTENT)
Route::get('/downloads/{distributive}/download', function (Distributive $distributive) {
    if (!$distributive->is_active || !$distributive->file_path) {
        abort(404, 'Дистрибутив не знайдено.');
    }

    if ($distributive->access_level === 'registered' && !Auth::check()) {
        return redirect()->route('login');
    }

    if ($distributive->access_level === 'licensed') {
        if (!Auth::check() || !Auth::user()->hasActiveLicense()) {
            abort(403, 'Відсутня ліцензія.');
        }
    }

    $fullPath = Storage::disk('public')->path($distributive->file_path);
    if (!file_exists($fullPath)) {
        abort(404, 'Файл фізично відсутній на сервері.');
    }

    $extension = pathinfo($distributive->file_path, PATHINFO_EXTENSION) ?: 'iso';
    $safeName = Str::slug($distributive->name, '_') ?: 'distributive';
    $downloadName = $safeName . '_v' . $distributive->version . '.' . $extension;

    // response()->download автоматично керує Range-заголовками, MIME-типами та докачуванням
    return response()->download($fullPath, $downloadName, [
        'Cache-Control' => 'no-store, no-cache, must-revalidate',
        'Pragma' => 'no-cache',
    ]);
})->name('distributives.download');

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

    return back()->with('success', "Дякуємо! Тікет #{$ticket->ticket_number} створено.");
});

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/cabinet');
        }
        return back()->withErrors(['email' => 'Помилка авторизації.']);
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
        $licenses = License::where('user_id', $user->id)->orWhere('client_email', $user->email)->get();
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
