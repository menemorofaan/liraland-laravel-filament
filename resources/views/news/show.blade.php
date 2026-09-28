@extends('layouts.app')

@section('title', $post->title . ' — Новини LIRALAND')

@section('content')
<article class="max-w-4xl mx-auto px-4 py-12">
    
    <div class="mb-6">
        <a href="/" class="text-xs font-bold text-[#0082c8] hover:underline">← Назад на головну</a>
        <div class="flex items-center space-x-3 text-xs text-slate-400 mt-3 font-semibold">
            <span>Опубліковано: {{ $post->published_at->format('d.m.Y') }}</span>
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2 leading-tight">
            {{ $post->title }}
        </h1>
    </div>

    @if($post->cover_image)
    <div class="mb-8 rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50 flex justify-center">
        <!-- h-auto и object-contain показывают картинку любого размера целиком, без принудительной обрезки браузером -->
        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[600px] object-contain">
    </div>
	@endif

    <!-- Вывод форматированного текста из визуального редактора -->
    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
        {!! $post->content !!}
    </div>

</article>
@endsection