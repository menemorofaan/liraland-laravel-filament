@extends('layouts.app')
@section('title', 'Особистий кабінет клієнта — LIRALAND')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    
    <div class="flex justify-between items-center mb-8 pb-4 border-b">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Особистий кабінет</h1>
            <p class="text-slate-500 text-sm">Користувач: <strong class="text-slate-800">{{ $user->name }}</strong> ({{ $user->email }})</p>
        </div>
        <div>
            @if($user->hasActiveLicense())
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-full">★ Активна ліцензія (VIP доступ)</span>
            @else
                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-3 py-1.5 rounded-full">Базовий користувач</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Секция: Мои лицензии -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center space-x-2">
                <span>🔑 Мої ліцензії та ключі захисту</span>
            </h2>

            <div class="space-y-4">
                @forelse($licenses as $lic)
                    <div class="p-4 rounded-xl border {{ $lic->status === 'active' ? 'border-sky-200 bg-sky-50/50' : 'border-slate-200 bg-slate-50' }}">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-mono text-xs font-bold text-[#0082c8] bg-white px-2 py-1 rounded border border-sky-100">
                                {{ $lic->license_key }}
                            </span>
                            <span class="text-xs px-2 py-0.5 font-bold rounded {{ $lic->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $lic->status === 'active' ? 'Активна' : 'Неактивна' }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-600 space-y-1 mt-3">
                            <p><strong>Тип:</strong> {{ $lic->type }} ({{ $lic->license_model === 'perpetual' ? 'Безстрокова з USB-ключем' : 'Хмарна підписка' }})</p>
                            @if($lic->dongle_id)
                                <p class="text-amber-800 font-medium"><strong>Апаратний номер ключа:</strong> {{ $lic->dongle_id }}</p>
                            @endif
                            <p><strong>Робочих місць:</strong> {{ $lic->max_devices }}</p>
                            <p><strong>Термін дії:</strong> {{ $lic->expires_at ? \Carbon\Carbon::parse($lic->expires_at)->format('d.m.Y') : 'Безстроково (Пожиттєва)' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs border border-dashed rounded-xl">
                        У вас поки немає зареєстрованих ліцензій.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Секция: Мои тикеты -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center space-x-2">
                <span>💬 Історія звернень до техпідтримки</span>
            </h2>

            <div class="space-y-3">
                @forelse($tickets as $tck)
                    <div class="p-4 rounded-xl border border-slate-200 text-xs">
                        <div class="flex justify-between mb-1">
                            <span class="font-bold text-slate-800">{{ $tck->ticket_number }}: {{ $tck->subject }}</span>
                            <span class="font-semibold text-slate-500">{{ $tck->status }}</span>
                        </div>
                        <p class="text-slate-400 text-[11px]">{{ $tck->created_at->format('d.m.Y H:i') }}</p>
                        @if($tck->admin_comment)
                            <div class="mt-2 p-2 bg-emerald-50 rounded text-emerald-900 border border-emerald-100">
                                <strong>Відповідь підтримки:</strong> {{ $tck->admin_comment }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs border border-dashed rounded-xl">
                        Немає активних звернень.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection