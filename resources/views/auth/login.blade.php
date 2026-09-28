@extends('layouts.app')
@section('title', 'Вхід до особистого кабінету — LIRALAND')
@section('content')
<div class="max-w-md mx-auto my-16 p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
    <h2 class="text-2xl font-bold text-slate-900 mb-6 text-center">Вхід в кабінет</h2>
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 text-xs rounded-lg">{{ $errors->first() }}</div>
    @endif
    <form action="/login" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
            <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#0082c8]">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Пароль</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#0082c8]">
        </div>
        <button type="submit" class="w-full bg-[#0082c8] hover:bg-sky-700 text-white font-bold py-2.5 rounded-lg text-sm transition">Увійти</button>
    </form>
    <p class="text-xs text-center text-slate-500 mt-4">Немає акаунту? <a href="/register" class="text-[#0082c8] font-semibold">Зареєструватися</a></p>
</div>
@endsection