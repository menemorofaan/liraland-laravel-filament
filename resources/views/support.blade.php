@extends('layouts.app')

@section('title', 'Служба технічної підтримки — LIRALAND')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Служба технічної підтримки</h1>
        <p class="text-slate-500 text-sm mt-2">Маєте запитання щодо ліцензій, ключів захисту або роботи ПЗ? Надішліть заявку.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center space-x-3">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form action="/tickets" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <!-- Исправили: четко просим Прізвище та ім'я -->
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Прізвище та ім'я *</label>
                    <input type="text" name="client_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0082c8] outline-none" placeholder="Ковальчук Іван">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Контактний Email *</label>
                    <input type="email" name="client_email" required class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0082c8] outline-none" placeholder="ivan@company.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Тема звернення *</label>
                    <input type="text" name="subject" required class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0082c8] outline-none" placeholder="Проблема з мережевим ключем CodeMeter">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Пріоритет</label>
                    <select name="priority" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0082c8] outline-none bg-white">
                        <option value="low">Низький</option>
                        <option value="medium" selected>Середній</option>
                        <option value="high">Високий</option>
                        <option value="critical">Критичний</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Опис проблеми *</label>
                <textarea name="message" rows="5" required class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0082c8] outline-none" placeholder="Вкажіть версію програми, номер ліцензії та детальний опис проблеми..."></textarea>
            </div>

            <button type="submit" class="w-full bg-[#0082c8] hover:bg-sky-700 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-sm">
                Надіслати звернення до підтримки
            </button>
        </form>
    </div>

</div>
@endsection