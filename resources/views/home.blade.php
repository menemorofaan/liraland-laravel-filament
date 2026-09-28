@extends('layouts.app')

@section('title', 'LIRALAND — Structural Engineering Software')

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-b from-slate-50 to-white py-16 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">
        
        <div class="lg:col-span-2 space-y-6">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Програмні комплекси для проектування будівельних конструкцій
            </h1>
            <p class="text-lg text-slate-600 leading-relaxed">
                Розрахунок та проектування будівельних і машинобудівних конструкцій будь-якої складності за сучасними світовими та європейськими нормами (Eurocodes, ДБН, DStU).
            </p>
            <div class="flex space-x-4 pt-2">
                <a href="/downloads" class="bg-[#0082c8] hover:bg-sky-700 text-white font-bold px-6 py-3 rounded-lg shadow transition">
                    Завантажити релізи →
                </a>
                <a href="/support" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-6 py-3 rounded-lg transition">
                    Зв'язатися з експертом
                </a>
            </div>
        </div>

        <!-- Боковой блок последних новостей (живые данные из базы) -->
<div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 shadow-sm">
    <h3 class="text-xs font-bold text-[#0082c8] uppercase tracking-wider mb-4 flex items-center justify-between">
        <span>Останні новини</span>
        <span>↓</span>
    </h3>
    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="border-b border-slate-200 last:border-0 pb-3 last:pb-0">
                <span class="text-[10px] text-slate-400 font-semibold uppercase">
                    {{ $post->published_at->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('news.show', $post->slug) }}" class="block text-sm font-bold text-slate-800 hover:text-[#0082c8] transition">
                    {{ $post->title }}
                </a>
                @if($post->summary)
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $post->summary }}</p>
                @endif
            </div>
        @empty
            <p class="text-xs text-slate-400 italic">Свіжих новин поки немає.</p>
        @endforelse
    </div>
</div>

    </div>
</div>

<!-- Блок флагманских продуктов (LIRA-FEM / LIRA-CAD) -->
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-gradient-to-r from-sky-700 to-cyan-600 rounded-2xl p-8 text-white shadow-md flex justify-between items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-sky-200">Флагманський розрахунковий комплекс</span>
                <h2 class="text-3xl font-black mt-1">ЛІРА-САПР</h2>
                <p class="text-sky-100 text-sm mt-2 max-w-sm">Багатофункціональний комплекс для розрахунку та проектування будівель.</p>
                <a href="/downloads" class="inline-block mt-4 bg-white text-sky-800 text-xs font-bold px-4 py-2 rounded-lg hover:bg-sky-50 transition">
                    Дистрибутиви &rarr;
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-8 text-white shadow-md flex justify-between items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-200">Архітектурне проектування</span>
                <h2 class="text-3xl font-black mt-1">САПФІР-3D</h2>
                <p class="text-teal-100 text-sm mt-2 max-w-sm">Створення тривимірних цифрових моделей будівель та генерація розрахункових схем.</p>
                <a href="/downloads" class="inline-block mt-4 bg-white text-teal-900 text-xs font-bold px-4 py-2 rounded-lg hover:bg-teal-50 transition">
                    Дистрибутиви &rarr;
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Блок реализованных проектов (Customer projects как на скриншоте) -->
<div class="bg-slate-50 py-16 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl font-bold text-slate-900">Проекти, розраховані в наших комплексах</h2>
            <p class="text-sm text-slate-500 mt-2">Тисячі інженерів по всьому світу щодня проектують унікальні споруди за допомогою сімейства програм LIRA</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                <div class="h-44 bg-slate-200 flex items-center justify-center text-slate-400 font-bold">Фото об'єкта 1</div>
                <div class="p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1">Висотний бізнес-центр</h3>
                    <p class="text-xs text-slate-500">Повний нелінійний динамічний розрахунок на сейсмічні впливи 8 балів.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                <div class="h-44 bg-slate-200 flex items-center justify-center text-slate-400 font-bold">Фото об'єкта 2</div>
                <div class="p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1">Гідроелектростанція</h3>
                    <p class="text-xs text-slate-500">Розрахунок масивних залізобетонних конструкцій з урахуванням тиску води.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                <div class="h-44 bg-slate-200 flex items-center justify-center text-slate-400 font-bold">Фото об'єкта 3</div>
                <div class="p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1">Багатоповерховий житловий комплекс</h3>
                    <p class="text-xs text-slate-500">BIM-моделювання та передача аналітичної моделі з САПФІР у ЛІРА-САПР.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection