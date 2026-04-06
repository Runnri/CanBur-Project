@extends('layouts.app')

@section('title', $destination->title)
@section('page-title', $destination->title)
@section('page-subtitle', 'Detail & Rencana Perjalanan')

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="{{ route('destinations.plans.create', $destination) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all duration-300 hover:shadow-lg hover:shadow-sand-400/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kegiatan
    </a>
    <a href="{{ route('destinations.edit', $destination) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all duration-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>
    <a href="{{ route('destinations.index') }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 text-white/60 font-body text-sm hover:border-white/20 hover:text-white transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- ══════════════════════════════════════ --}}
    {{-- HERO CARD DESTINASI                   --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="card rounded-3xl overflow-hidden">
        <div class="relative h-56 md:h-64">
            @if($destination->image)
                <img src="{{ asset('storage/' . $destination->image) }}"
                     alt="{{ $destination->title }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-forest-700 via-forest-800 to-forest-900 flex items-center justify-center">
                    <span class="text-8xl opacity-60">🏝️</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-forest-900 via-forest-900/20 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end justify-between">
                <div>
                    <h2 class="font-display text-3xl font-black">{{ $destination->title }}</h2>
                    <p class="text-white/50 font-body text-sm mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($destination->departure_date)->translatedFormat('d F Y') }}
                    </p>
                </div>
                @if($destination->is_completed)
                    <span class="px-3 py-1.5 rounded-full bg-green-500/25 border border-green-500/40 text-green-400 text-sm font-body backdrop-blur-sm">
                        ✓ Tercapai
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/60 text-sm font-body backdrop-blur-sm">
                        ○ Belum Tercapai
                    </span>
                @endif
            </div>
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-3 divide-x divide-white/5 border-t border-white/5">
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Durasi</p>
                <p class="font-display text-2xl font-bold">
                    {{ $destination->duration }}
                    <span class="text-sm font-body text-white/40">Hari</span>
                </p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Budget</p>
                <p class="font-display text-lg font-bold text-sand-400">
                    Rp {{ number_format($destination->budget, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Total Kegiatan</p>
                <p class="font-display text-2xl font-bold">{{ $destination->plans->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- PLANS SECTION                         --}}
    {{-- ══════════════════════════════════════ --}}
    <div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-display text-xl font-bold">Rencana Perjalanan</h3>
                <p class="text-white/30 font-body text-xs mt-0.5">Jadwal kegiatan per hari & jam</p>
            </div>
            <a href="{{ route('destinations.plans.create', $destination) }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all duration-300 hover:shadow-lg hover:shadow-sand-400/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan
            </a>
        </div>

        {{-- Empty state --}}
        @if($destination->plans->isEmpty())
        <div class="card rounded-3xl p-16 text-center">
            <div class="text-5xl mb-4 animate-float inline-block">📅</div>
            <h4 class="font-display text-xl font-bold mb-2">Belum ada rencana perjalanan</h4>
            <p class="text-white/40 font-body text-sm mb-6 max-w-xs mx-auto">
                Mulai tambahkan jadwal kegiatan untuk setiap hari perjalananmu.
            </p>
            <a href="{{ route('destinations.plans.create', $destination) }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-sand-400/15 border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan Pertama
            </a>
        </div>

        @else

        {{-- ── GROUP BY HARI ── --}}
        @php
            $grouped = $destination->plans
                ->sortBy(['hari', 'jam'])
                ->groupBy('hari');
        @endphp

        <div class="space-y-4">
            @foreach($grouped as $hari => $activities)
            <div class="card rounded-2xl overflow-hidden">

                {{-- Day header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sand-400/15 border border-sand-400/25 flex items-center justify-center shrink-0">
                            <span class="font-display font-black text-sand-400 text-sm">{{ $hari }}</span>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-base">Hari ke-{{ $hari }}</h4>
                            <p class="text-white/30 text-xs font-body">
                                {{ \Carbon\Carbon::parse($destination->departure_date)->addDays($hari - 1)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-body text-white/25">{{ $activities->count() }} kegiatan</span>
                        <a href="{{ route('destinations.plans.create', ['destination' => $destination, 'hari' => $hari]) }}"
                           class="w-7 h-7 rounded-lg bg-sand-400/15 hover:bg-sand-400 hover:text-forest-900 text-sand-400 flex items-center justify-center transition-all duration-200 text-base font-bold leading-none"
                           title="Tambah kegiatan hari {{ $hari }}">
                            +
                        </a>
                    </div>
                </div>

                {{-- Activities list --}}
                <div class="divide-y divide-white/5">
                    @foreach($activities->sortBy('jam') as $plan)
                    <div class="flex items-start gap-4 px-5 py-4 group hover:bg-white/[0.02] transition-colors duration-150">

                        {{-- Jam --}}
                        <div class="shrink-0 w-12 text-right pt-0.5">
                            <span class="text-sand-400 font-body text-sm font-medium tabular-nums">
                                {{ \Carbon\Carbon::parse($plan->jam)->format('H:i') }}
                            </span>
                        </div>

                        {{-- Dot --}}
                        <div class="shrink-0 mt-[7px]">
                            <div class="w-2.5 h-2.5 rounded-full border-2 border-sand-400 bg-sand-400/30"></div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0 pt-0.5">
                            <p class="font-body text-sm text-white/80 leading-relaxed break-words">
                                {{ $plan->kegiatan }}
                            </p>
                            @if($plan->lokasi)
                            <p class="text-white/30 text-xs font-body mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                {{ $plan->lokasi }}
                            </p>
                            @endif
                        </div>

                        {{-- Actions (muncul saat hover) --}}
                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            {{-- Edit --}}
                            <a href="{{ route('destinations.plans.edit', [$destination, $plan]) }}"
                               class="w-7 h-7 rounded-lg bg-white/5 hover:bg-sand-400/20 hover:text-sand-400 text-white/30 flex items-center justify-center transition-all"
                               title="Edit kegiatan">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            {{-- Hapus --}}
                            <form method="POST"
                                  action="{{ route('destinations.plans.destroy', [$destination, $plan]) }}"
                                  onsubmit="return confirm('Hapus kegiatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-white/5 hover:bg-red-500/20 hover:text-red-400 text-white/30 flex items-center justify-center transition-all"
                                        title="Hapus kegiatan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>

            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
