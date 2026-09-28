<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LIRALAND Group — Програмні комплекси для розрахунку будівельних конструкцій')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-800 font-sans flex flex-col min-h-screen">

    <!-- Верхнее навигационное меню (как на оригинальном сайте) -->
    <header class="bg-[#0082c8] text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-2xl font-black tracking-wider text-white">LIRALAND</span>
                    <span class="text-xs uppercase tracking-widest text-sky-200">Group</span>
                </a>
                <nav class="hidden md:flex space-x-6 text-sm font-medium">
                    <a href="/" class="hover:text-sky-200 transition">Головна</a>
                    <a href="/downloads" class="hover:text-sky-200 transition">Завантаження</a>
                    <a href="/support" class="hover:text-sky-200 transition">Підтримка</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4 text-xs font-semibold">
    @auth
        <a href="/cabinet" class="bg-white text-[#0082c8] hover:bg-sky-50 px-3 py-1.5 rounded transition">
            Кабінет ({{ Auth::user()->name }})
        </a>
        <form action="/logout" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sky-200 hover:text-white">Вийти</button>
        </form>
    @else
        <a href="/login" class="text-white hover:text-sky-200">Увійти</a>
        <a href="/register" class="bg-white text-[#0082c8] hover:bg-sky-50 px-3 py-1.5 rounded transition">Реєстрація</a>
    @endauth
			</div>
        </div>
    </header>

    <!-- Основное содержимое конкретной страницы -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Подвал (Footer) -->
    <footer class="bg-slate-900 text-slate-400 text-xs mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-white font-bold text-sm mb-3 uppercase tracking-wider">Продукти</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white">ЛІРА-САПР</a></li>
                    <li><a href="#" class="hover:text-white">САПФІР</a></li>
                    <li><a href="#" class="hover:text-white">МОНОМАХ-САПР</a></li>
                    <li><a href="#" class="hover:text-white">ЕСПРІ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-3 uppercase tracking-wider">Підтримка</h4>
                <ul class="space-y-2">
                    <li><a href="/downloads" class="hover:text-white">Дистрибутиви ПЗ</a></li>
                    <li><a href="/support" class="hover:text-white">Технічна підтримка</a></li>
                    <li><a href="#" class="hover:text-white">База знань</a></li>
                    <li><a href="#" class="hover:text-white">Навчальні курси</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-3 uppercase tracking-wider">Компанія</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white">Про нас</a></li>
                    <li><a href="#" class="hover:text-white">Новини</a></li>
                    <li><a href="#" class="hover:text-white">Контакти</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-3 uppercase tracking-wider">Контакти</h4>
                <p class="mb-2">Email: info@liraland.com</p>
                <p>Тел: +38 (044) 590-58-85</p>
            </div>
        </div>
        <div class="border-t border-slate-800 py-4 text-center text-slate-500">
            &copy; 2002–{{ date('Y') }} LIRALAND Group. Всі права захищено.
        </div>
    </footer>

</body>
</html>