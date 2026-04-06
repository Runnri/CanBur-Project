@extends('layouts.app')

@section('title', 'Tambah Kegiatan')
@section('page-title', 'Tambah Kegiatan')
@section('page-subtitle', $destination->judul)

@section('header-actions')
    <a href="{{ route('destinations.show', $destination) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 text-white/60 font-body text-sm hover:border-white/20 hover:text-white transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Breadcrumb info destinasi --}}
    <div class="card rounded-2xl px-5 py-3.5 flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-lg bg-sand-400/15 border border-sand-400/20 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-sand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-white/30 font-body">Menambah kegiatan untuk destinasi</p>
            <p class="text-sm font-body font-medium text-white truncate">{{ $destination->judul }}</p>
        </div>
        <span class="text-xs font-body text-white/30 shrink-0">
            Maks. {{ $destination->lama_hari }} hari
        </span>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('destinations.plans.store', $destination) }}" autocomplete="off>"
        @csrf

        <div class="space-y-5">

            <div class="card rounded-3xl p-6 space-y-5">

                {{-- Hari --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Hari ke- <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        {{-- Tombol kurang --}}
                        <button type="button"
                                onclick="let el = document.getElementById('input-hari'); if(el.value > 1) el.value--"
                                class="w-11 h-11 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all text-xl font-light shrink-0">
                            −
                        </button>
                        <input type="number"
                               id="input-hari"
                               name="hari"
                               value="{{ old('hari', request('hari', 1)) }}"
                               min="1"
                               max="{{ $destination->lama_hari }}"
                               class="input-field flex-1 px-4 py-3 rounded-xl font-body text-sm text-center @error('hari') border-red-500/50 @enderror">
                        {{-- Tombol tambah --}}
                        <button type="button"
                                onclick="let el = document.getElementById('input-hari'); if(el.value < {{ $destination->lama_hari }}) el.value++"
                                class="w-11 h-11 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all text-xl font-light shrink-0">
                            +
                        </button>
                    </div>
                    @error('hari')
                        <p class="text-red-400 text-xs font-body flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jam --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Jam <span class="text-red-400">*</span>
                    </label>
                    <input type="time"
                           name="jam"
                           value="{{ old('jam', '07:00') }}"
                           class="input-field w-full px-4 py-3 rounded-xl font-body text-sm @error('jam') border-red-500/50 @enderror"
                           style="color-scheme: dark;">
                    @error('jam')
                        <p class="text-red-400 text-xs font-body flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Kegiatan <span class="text-red-400">*</span>
                    </label>
                    <textarea name="kegiatan"
                              rows="3"
                              maxlength="500"
                              placeholder="Contoh: Berangkat naik kereta dari Jakarta menuju Malang"
                              class="input-field w-full px-4 py-3 rounded-xl font-body text-sm resize-none leading-relaxed @error('kegiatan') border-red-500/50 @enderror">{{ old('kegiatan') }}</textarea>
                    @error('kegiatan')
                        <p class="text-red-400 text-xs font-body flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Lokasi
                        <span class="normal-case tracking-normal text-white/25 ml-1">(opsional)</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <input type="text"
                               name="lokasi"
                               value="{{ old('lokasi') }}"
                               placeholder="Stasiun Gambir, Jakarta"
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl font-body text-sm @error('lokasi') border-red-500/50 @enderror">
                    </div>
                    @error('lokasi')
                        <p class="text-red-400 text-xs font-body mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-between pt-1">
                <a href="{{ route('destinations.show', $destination) }}"
                   class="text-white/40 font-body text-sm hover:text-white transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-8 py-3 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-xl hover:shadow-sand-400/20 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kegiatan
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
