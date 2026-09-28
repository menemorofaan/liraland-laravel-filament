@extends('layouts.app')

@section('title', 'Центр завантаження ПЗ — LIRALAND')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Центр завантаження дистрибутивів</h1>
        <p class="text-slate-500 text-sm mt-1">Офіційні релізи, драйвери ключів захисту та оновлення</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($distributives as $item)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-bold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md">
                            {{ $item->os }}
                        </span>
                        
                        <!-- Бейдж доступа -->
                        @if($item->access_level === 'public')
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Вільний доступ</span>
                        @elseif($item->access_level === 'registered')
                            <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Для зареєстрованих</span>
                        @else
                            <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">Тільки з ліцензією</span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $item->name }}</h3>
                    <p class="text-xs text-[#0082c8] font-bold mb-4">Версія {{ $item->version }}</p>
                </div>

                <!-- Логика скачивания по уровням -->
                <div class="mt-4">
    @if($item->access_level === 'public')
        <a href="{{ route('distributives.download', $item->id) }}" class="w-full text-center bg-[#0082c8] hover:bg-sky-700 text-white text-xs font-bold py-2.5 rounded-lg transition block">
            Завантажити (Вільний доступ)
        </a>
    @elseif($item->access_level === 'registered')
        @auth
            <a href="{{ route('distributives.download', $item->id) }}" class="w-full text-center bg-[#0082c8] hover:bg-sky-700 text-white text-xs font-bold py-2.5 rounded-lg transition block">
                Завантажити
            </a>
        @else
            <a href="/login" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold py-2.5 rounded-lg transition block">
                Увійдіть для завантаження
            </a>
        @endauth
    @elseif($item->access_level === 'licensed')
        @if(Auth::check() && Auth::user()->hasActiveLicense())
            <a href="{{ route('distributives.download', $item->id) }}" class="w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-lg transition block">
                Завантажити реліз (VIP)
            </a>
        @elseif(Auth::check())
            <a href="/support" class="w-full text-center bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-semibold py-2.5 rounded-lg transition block">
                🔒 Замовити ліцензію
            </a>
        @else
            <a href="/login" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold py-2.5 rounded-lg transition block">
                Увійдіть для перевірки ліцензії
            </a>
        @endif
    @endif
</div>

            </div>
        @empty
            <div class="col-span-full bg-slate-50 p-12 rounded-2xl text-center text-slate-400 text-sm">
                Дистрибутиви відсутні.
            </div>
        @endforelse
    </div>
</div>
@endsection