@extends('layouts.app')

@section('title', 'Destinasi Liburan')
@section('page-title', 'Destinasi Liburan')
@section('page-subtitle', 'Kelola semua tujuan perjalananmu')


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
<div class="space-y-6 animate-fade-in">

    {{-- Filter & Search --}}
    {{-- Filter Status (100% HTML & Blade Murni, Anti-Gagal) --}}
    <form method="GET" action="{{ route('destinations.index') }}" class="card rounded-2xl p-4 flex flex-wrap gap-2">
        
        {{-- Looping Tombol Filter --}}
        @foreach([['all','Semua'],['tercapai','Tercapai'],['belum','Belum']] as [$val, $label])
            @php
                // Cek apakah tombol ini adalah filter yang sedang aktif di URL
                $isActive = request('filter', 'all') === $val;
            @endphp
            
            {{-- Kita jadikan tombol ini sebagai tombol Submit seutuhnya --}}
            <button type="submit" 
                    name="filter" 
                    value="{{ $val }}"
                    class="px-4 py-2 rounded-xl font-body text-sm transition-all duration-200 {{ $isActive ? 'bg-sand-400 text-forest-900 shadow-md shadow-sand-400/20' : 'bg-white/5 text-white/50 hover:bg-white/10' }}">
                {{ $label }}
            </button>
        @endforeach
        
    </form>

    {{-- Destinations grid --}}
    @if($destinations->isEmpty())
    <div class="card rounded-3xl p-20 text-center">
        <span class="text-6xl block mb-4 animate-float">🗺️</span>
        <h3 class="font-display text-2xl font-bold mb-2">Belum ada destinasi</h3>
        <p class="text-white/40 font-body mb-8 max-w-xs mx-auto">Tambahkan destinasi liburan pertamamu sekarang!</p>
        <a href="{{ route('destinations.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all">
            + Tambah Destinasi
        </a>
    </div>
    @else
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($destinations as $destination)
        <div class="dest-card rounded-3xl overflow-hidden group">
            {{-- Image --}}
            <div class="relative h-48 overflow-hidden">
                @if($destination->image)
                    <img src="{{ asset('storage/' . $destination->image) }}"
                         alt="{{ $destination->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-forest-700 via-forest-800 to-forest-900 flex items-center justify-center">
             
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-forest-900/80 via-transparent to-transparent"></div>

                {{-- Status --}}
                <div class="absolute top-3 left-3">
                    @if($destination->is_completed)
                        <span class="px-2.5 py-1 rounded-full bg-green-500/25 border border-green-500/40 text-green-400 text-[11px] font-body backdrop-blur-sm">✓ Tercapai</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full bg-black/30 border border-white/15 text-white/60 text-[11px] font-body backdrop-blur-sm">○ Belum</span>
                    @endif
                </div>

                {{-- Quick actions on hover --}}
                <div class="absolute top-3 right-3 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0">
                    <a href="{{ route('destinations.show', $destination) }}"
                       class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-sand-400 hover:border-sand-400 hover:text-forest-900 text-white transition-all"
                       title="Detail">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('destinations.edit', $destination) }}"
                       class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-sand-400 hover:border-sand-400 hover:text-forest-900 text-white transition-all"
                       title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('destinations.destroy', $destination) }}"
                          onsubmit="return confirm('Hapus destinasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-red-500 hover:border-red-500 text-white transition-all"
                                title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Days pill on image --}}
                <div class="absolute bottom-3 left-3">
                    <span class="px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 text-white/70 text-[11px] font-body">
                        {{ $destination->duration }} Hari
                    </span>
                </div>
            </div>

            {{-- Card body --}}
            <div class="p-5 space-y-3">
                <div>
                    <h3 class="font-display text-lg font-bold leading-tight">{{ $destination->title }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <p class="text-white/30 text-xs font-body">{{ \Carbon\Carbon::parse($destination->departure_date)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-white/30 font-body uppercase tracking-wide">Budget</p>
                        <p class="text-sand-400 font-display font-bold text-lg">
                            Rp {{ number_format($destination->budget, 0, ',', '.') }}
                        </p>
                    </div>
                    <a href="{{ route('destinations.show', $destination) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-sand-400/30 text-sand-400 text-xs font-body hover:bg-sand-400 hover:text-forest-900 transition-all duration-200">
                        Rencana
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($destinations->hasPages())
    <div class="flex justify-center pt-4">
        {{ $destinations->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
