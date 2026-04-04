@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle') Halo {{ Auth::user()->name }}! Siap liburan hari ini? @endsection


@section('header-actions')
    <a href="{{ route('destinations.create') }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all duration-300 hover:shadow-lg hover:shadow-sand-400/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Destinasi
    </a>
@endsection

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- Stats row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Total Destinasi', 'value' => $destinations->count(), 'icon' => '🗺️', 'color' => 'sand'],
                ['label' => 'Sudah Tercapai', 'value' => $destinations->where('is_completed', true)->count(), 'icon' => '✅', 'color' => 'green'], 
                ['label' => 'Belum Tercapai', 'value' => $destinations->where('is_completed', false)->count(), 'icon' => '⏳', 'color' => 'yellow'],
                ['label' => 'Total Rencana', 'value' => $totalPlans ?? 0, 'icon' => '📅', 'color' => 'blue'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="card rounded-2xl p-5 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-2xl">{{ $stat['icon'] }}</span>
                @if($stat['color'] === 'sand')
                    <span class="w-2 h-2 rounded-full bg-sand-400"></span>
                @elseif($stat['color'] === 'green')
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                @elseif($stat['color'] === 'yellow')
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                @else
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                @endif
            </div>
            <div>
                <p class="font-display text-3xl font-black">{{ $stat['value'] }}</p>
                <p class="text-white/40 text-xs font-body mt-1">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Destinations section --}}
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-2xl font-bold">Destinasi Liburanmu</h2>
                <p class="text-white/40 font-body text-sm mt-1">Semua rencana perjalananmu dalam satu tempat</p>
            </div>
            <a href="{{ route('destinations.index') }}" class="text-sand-400 font-body text-sm hover:text-sand-300 transition-colors flex items-center gap-1">
                Lihat semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($destinations->isEmpty())
        {{-- Empty state --}}
        <div class="card rounded-3xl p-16 text-center">
            <span class="text-6xl block mb-4 animate-float">🌍</span>
            <h3 class="font-display text-2xl font-bold mb-2">Belum ada destinasi</h3>
            <p class="text-white/40 font-body mb-8 max-w-sm mx-auto">Mulai tambahkan destinasi liburan impianmu dan rencanakan perjalanan yang tak terlupakan.</p>
            <a href="{{ route('destinations.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Destinasi Pertama
            </a>
        </div>
        @else
        {{-- Cards grid --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($destinations->take(6) as $destination)
            <div class="dest-card rounded-3xl overflow-hidden group">
                {{-- Image --}}
                <div class="relative h-44 overflow-hidden">
                    @if($destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}"
                             alt="{{ $destination->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-forest-700 to-forest-800 flex items-center justify-center">
                            <span class="text-5xl">🏝️</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-forest-900/70 via-transparent to-transparent"></div>

                    {{-- Status badge --}}
                    <div class="absolute top-3 right-3">
                        @if($destination->is_completed)
                            <span class="px-2.5 py-1 rounded-full bg-green-500/25 border border-green-500/40 text-green-400 text-[11px] font-body backdrop-blur-sm">✓ Tercapai</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-black/30 border border-white/15 text-white/60 text-[11px] font-body backdrop-blur-sm">○ Belum</span>
                        @endif
                    </div>

                    {{-- Actions overlay --}}
                    <div class="absolute bottom-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0">
                        <a href="{{ route('destinations.show', $destination) }}"
                           class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-sand-400 hover:border-sand-400 hover:text-forest-900 transition-all"
                           title="Lihat Detail">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('destinations.edit', $destination) }}"
                           class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-sand-400 hover:border-sand-400 hover:text-forest-900 transition-all"
                           title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-4 space-y-3">
                    <div>
                        <h3 class="font-display text-base font-bold leading-tight">{{ $destination->title }}</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center p-2 rounded-xl" style="background:rgba(255,255,255,0.04)">
                            <p class="text-[9px] text-white/30 font-body uppercase tracking-wide">Tanggal</p>
                            <p class="text-xs font-body font-medium mt-0.5 text-white/80">{{ \Carbon\Carbon::parse($destination->departure_date)->format('d M') }}</p>
                        </div>
                        <div class="text-center p-2 rounded-xl" style="background:rgba(255,255,255,0.04)">
                            <p class="text-[9px] text-white/30 font-body uppercase tracking-wide">Durasi</p>
                            <p class="text-xs font-body font-medium mt-0.5 text-white/80">{{ $destination->duration }}h</p>
                        </div>
                        <div class="text-center p-2 rounded-xl" style="background:rgba(212,158,82,0.1)">
                            <p class="text-[9px] text-sand-400/60 font-body uppercase tracking-wide">Budget</p>
                            <p class="text-xs font-body font-medium mt-0.5 text-sand-400">{{ number_format($destination->budget, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div class="flex items-center justify-between pt-1 border-t border-white/5">
                        <a href="{{ route('destinations.show', $destination) }}"
                           class="text-xs font-body text-white/40 hover:text-sand-400 transition-colors flex items-center gap-1">
                            Lihat rencana
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('destinations.destroy', $destination) }}"
                              onsubmit="return confirm('Hapus destinasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-body text-red-400/40 hover:text-red-400 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
