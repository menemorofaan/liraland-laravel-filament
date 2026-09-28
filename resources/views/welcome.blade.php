<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корпоративний портал | LIRALAND</title>
    <!-- Подключаем Tailwind CSS для красивого оформления -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans">

    <!-- Шапка сайта -->
    <header class="bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="text-2xl font-black text-amber-500 tracking-wider">LIRALAND</span>
                <span class="text-xs bg-slate-800 text-slate-300 px-2 py-1 rounded">Portal</span>
            </div>
            <a href="/admin" class="text-sm font-medium bg-amber-500 hover:bg-amber-600 text-slate-950 px-4 py-2 rounded-lg transition">
                Вхід в адмінку →
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10 space-y-12">

        <!-- Секция 1: Загрузка дистрибутивов -->
        <section>
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Центр завантаження ПЗ</h2>
                <p class="text-slate-500 text-sm">Офіційні релізи та оновлення програмних комплексів</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($distributives as $item)
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md">
                                    {{ $item->os }}
                                </span>
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">
                                    v{{ $item->version }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $item->name }}</h3>
                            <p class="text-xs text-slate-400 mb-4">Оновлено: {{ $item->updated_at->format('d.m.Y') }}</p>
                        </div>

                        @if($item->file_path)
                            <a href="{{ asset('storage/' . $item->file_path) }}" download class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold py-2.5 rounded-lg transition flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Завантажити інсталятор</span>
                            </a>
                        @else
                            <button disabled class="w-full bg-slate-100 text-slate-400 text-sm py-2.5 rounded-lg cursor-not-allowed">
                                Файл недоступний
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-xl border border-dashed border-slate-300 text-center text-slate-500">
                        Наразі немає доступних дистрибутивів для завантаження.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Секция 2: Форма техподдержки -->
        <section class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm max-w-2xl mx-auto">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-slate-900">Служба технічної підтримки</h2>
                <p class="text-slate-500 text-sm mt-1">Виникли питання щодо ліцензування або роботи софту? Напишіть нам.</p>
            </div>

            <!-- Уведомление об успешной отправке -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="/tickets" method="POST" class="space-y-4">
                <!-- Защита от CSRF атак (Обязательно в Laravel!) -->
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ваше Ім'я</label>
                        <input type="text" name="client_name" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none" placeholder="Іван Ковальчук">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
                        <input type="email" name="client_email" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none" placeholder="ivan@company.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Тема звернення</label>
                        <input type="text" name="subject" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none" placeholder="Помилка при активації ліцензії">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Пріоритет</label>
                        <select name="priority" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none bg-white">
                            <option value="low">Низький</option>
                            <option value="medium" selected>Середній</option>
                            <option value="high">Високий</option>
                            <option value="critical">Критичний</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Опис проблеми</label>
                    <textarea name="message" rows="4" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none" placeholder="Опишіть вашу ситуацію або вкажіть код помилки..."></textarea>
                </div>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-lg text-sm transition shadow-sm">
                    Надіслати звернення
                </button>
            </form>
        </section>

    </main>

    <footer class="text-center py-6 text-xs text-slate-400 border-t border-slate-200 mt-12">
        &copy; {{ date('Y') }} LIRALAND Group. Прототип корпоративної платформи (Laravel 11 + Filament).
    </footer>

</body>
</html>