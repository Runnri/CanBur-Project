@extends('layouts.app')

@section('title', $destination->judul)
@section('page-title', $destination->judul)
@section('page-subtitle', 'Detail & Rencana Perjalanan')

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="{{ route('destinations.edit', $destination) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all duration-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Destinasi
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
<div class="space-y-6" x-data="planManager()" x-init="init()">

    {{-- ══════════════════════════════════════ --}}
    {{-- DESTINATION HERO CARD                 --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="card rounded-3xl overflow-hidden">
        <div class="relative h-56 md:h-64">
            @if($destination->foto)
                <img src="{{ asset('storage/' . $destination->foto) }}"
                     alt="{{ $destination->judul }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-forest-700 via-forest-800 to-forest-900 flex items-center justify-center">
                    <span class="text-8xl opacity-60">🏝️</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-forest-900 via-forest-900/20 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end justify-between">
                <div>
                    <h2 class="font-display text-3xl font-black">{{ $destination->judul }}</h2>
                    <p class="text-white/50 font-body text-sm mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($destination->tanggal_berangkat)->translatedFormat('d F Y') }}
                    </p>
                </div>
                @if($destination->status)
                    <span class="px-3 py-1.5 rounded-full bg-green-500/25 border border-green-500/40 text-green-400 text-sm font-body">✓ Tercapai</span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/60 text-sm font-body">○ Belum Tercapai</span>
                @endif
            </div>
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-3 divide-x divide-white/5 border-t border-white/5">
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Durasi</p>
                <p class="font-display text-2xl font-bold">{{ $destination->lama_hari }}
                    <span class="text-sm font-body text-white/40">Hari</span></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Budget</p>
                <p class="font-display text-lg font-bold text-sand-400">
                    Rp {{ number_format($destination->budget, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Total Kegiatan</p>
                <p class="font-display text-2xl font-bold" x-text="plans.length"></p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- PLANS SECTION                         --}}
    {{-- ══════════════════════════════════════ --}}
    <div>
        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-display text-xl font-bold">Rencana Perjalanan</h3>
                <p class="text-white/30 font-body text-xs mt-0.5">Jadwal kegiatan per hari & jam</p>
            </div>
            <button @click="openAdd()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all duration-300 hover:shadow-lg hover:shadow-sand-400/20 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan
            </button>
        </div>

        {{-- Day filter tabs --}}
        <div x-show="groupedPlans.length > 0"
             class="flex gap-2 overflow-x-auto pb-3 mb-5"
             style="-ms-overflow-style:none; scrollbar-width:none;">
            <button @click="activeDay = null"
                    :class="activeDay === null
                        ? 'bg-sand-400 text-forest-900 border-sand-400'
                        : 'bg-white/5 text-white/50 border-white/10 hover:border-white/20 hover:text-white/70'"
                    class="shrink-0 px-4 py-1.5 rounded-full border font-body text-xs transition-all duration-200">
                Semua Hari
            </button>
            <template x-for="group in groupedPlans" :key="group.hari">
                <button @click="activeDay = group.hari"
                        :class="activeDay === group.hari
                            ? 'bg-sand-400 text-forest-900 border-sand-400'
                            : 'bg-white/5 text-white/50 border-white/10 hover:border-white/20 hover:text-white/70'"
                        class="shrink-0 px-4 py-1.5 rounded-full border font-body text-xs transition-all duration-200"
                        x-text="`Hari ${group.hari}`">
                </button>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="groupedPlans.length === 0" class="card rounded-3xl p-16 text-center">
            <div class="text-5xl mb-4" style="display:inline-block; animation: float 4s ease-in-out infinite;">📅</div>
            <h4 class="font-display text-xl font-bold mb-2">Belum ada rencana perjalanan</h4>
            <p class="text-white/40 font-body text-sm mb-6 max-w-xs mx-auto">
                Mulai tambahkan jadwal kegiatan untuk setiap hari perjalananmu.
            </p>
            <button @click="openAdd()"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-sand-400/15 border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan Pertama
            </button>
        </div>

        {{-- Timeline grouped by day --}}
        <div x-show="groupedPlans.length > 0" class="space-y-4">
            <template x-for="group in filteredGroups" :key="group.hari">
                <div class="card rounded-2xl overflow-hidden">

                    {{-- Day header --}}
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sand-400/15 border border-sand-400/25 flex items-center justify-center shrink-0">
                                <span class="font-display font-black text-sand-400 text-sm" x-text="group.hari"></span>
                            </div>
                            <div>
                                <h4 class="font-display font-bold text-base" x-text="`Hari ke-${group.hari}`"></h4>
                                <p class="text-white/30 text-xs font-body" x-text="getDayDate(group.hari)"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-body text-white/25"
                                  x-text="`${group.items.length} kegiatan`"></span>
                            <button @click="openAdd(group.hari)"
                                    class="w-7 h-7 rounded-lg bg-sand-400/15 hover:bg-sand-400 hover:text-forest-900 text-sand-400 flex items-center justify-center transition-all duration-200 text-base font-bold leading-none"
                                    title="Tambah kegiatan hari ini">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Activities --}}
                    <div class="divide-y divide-white/5">
                        <template x-for="plan in group.items" :key="plan.id">
                            <div class="flex items-start gap-4 px-5 py-4 group hover:bg-white/[0.02] transition-colors duration-150">

                                {{-- Time --}}
                                <div class="shrink-0 w-12 text-right pt-0.5">
                                    <span class="text-sand-400 font-body text-sm font-medium tabular-nums"
                                          x-text="formatTime(plan.jam)"></span>
                                </div>

                                {{-- Dot --}}
                                <div class="shrink-0 mt-[7px]">
                                    <div class="w-2.5 h-2.5 rounded-full border-2 border-sand-400 bg-sand-400/30"></div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="font-body text-sm text-white/80 leading-relaxed break-words"
                                       x-text="plan.kegiatan"></p>
                                    <div x-show="plan.lokasi" class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-white/25 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                        <span class="text-white/30 text-xs font-body" x-text="plan.lokasi"></span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button @click="openEdit(plan)"
                                            class="w-7 h-7 rounded-lg bg-white/5 hover:bg-sand-400/20 hover:text-sand-400 text-white/30 flex items-center justify-center transition-all"
                                            title="Edit kegiatan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="promptDelete(plan.id)"
                                            class="w-7 h-7 rounded-lg bg-white/5 hover:bg-red-500/20 hover:text-red-400 text-white/30 flex items-center justify-center transition-all"
                                            title="Hapus kegiatan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </template>
        </div>
    </div>


    {{-- ══════════════════════════════════════ --}}
    {{-- MODAL TAMBAH / EDIT                   --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="showModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative w-full max-w-md z-10 rounded-3xl p-6 shadow-2xl"
             style="background: linear-gradient(135deg, #102318 0%, #0a160f 100%); border: 1px solid rgba(255,255,255,0.1);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal header --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-display text-lg font-bold"
                        x-text="isEditing ? 'Edit Kegiatan' : 'Tambah Kegiatan'"></h3>
                    <p class="text-white/30 font-body text-xs mt-0.5"
                       x-text="isEditing ? 'Perbarui detail kegiatan' : 'Isi jadwal kegiatan perjalananmu'"></p>
                </div>
                <button @click="closeModal()"
                        class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/40 hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Error --}}
            <div x-show="errorMsg" x-cloak
                 class="mb-4 flex items-start gap-2 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 font-body text-xs leading-relaxed">
                <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="errorMsg"></span>
            </div>

            {{-- Fields --}}
            <div class="space-y-4">

                {{-- Hari --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Hari ke- <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                @click="if(form.hari > 1) form.hari--"
                                class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all text-lg font-light">
                            −
                        </button>
                        <input type="number" x-model.number="form.hari"
                               min="1" max="{{ $destination->lama_hari }}"
                               class="input-field flex-1 px-4 py-2.5 rounded-xl font-body text-sm text-center tabular-nums">
                        <button type="button"
                                @click="if(form.hari < {{ $destination->lama_hari }}) form.hari++"
                                class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all text-lg font-light">
                            +
                        </button>
                    </div>
                    <p class="text-white/20 text-xs font-body">
                        Maks. {{ $destination->lama_hari }} hari
                        <span class="mx-1">·</span>
                        <span x-text="getDayDate(form.hari)" class="text-white/30"></span>
                    </p>
                </div>

                {{-- Jam --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Jam <span class="text-red-400">*</span>
                    </label>
                    <input type="time" x-model="form.jam"
                           class="input-field w-full px-4 py-3 rounded-xl font-body text-sm"
                           style="color-scheme: dark;">
                </div>

                {{-- Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">
                        Kegiatan <span class="text-red-400">*</span>
                    </label>
                    <textarea x-model="form.kegiatan" rows="3"
                              placeholder="Contoh: Berangkat naik kereta dari Jakarta menuju Malang"
                              class="input-field w-full px-4 py-3 rounded-xl font-body text-sm resize-none leading-relaxed"
                              maxlength="500"></textarea>
                    <div class="flex justify-end">
                        <span class="text-white/20 text-xs font-body"
                              :class="form.kegiatan.length > 450 ? 'text-yellow-400/60' : ''"
                              x-text="`${form.kegiatan.length}/500`"></span>
                    </div>
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
                        <input type="text" x-model="form.lokasi"
                               placeholder="Stasiun Gambir, Jakarta"
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl font-body text-sm">
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between mt-6 pt-5 border-t border-white/5">
                <button @click="closeModal()"
                        class="text-white/40 font-body text-sm hover:text-white transition-colors">
                    Batal
                </button>
                <button @click="submitForm()"
                        :disabled="submitting"
                        class="flex items-center gap-2 px-7 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed hover:bg-sand-300 hover:shadow-lg hover:shadow-sand-400/20 hover:-translate-y-0.5">
                    <svg x-show="submitting" class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg x-show="!submitting" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Tambah Kegiatan')"></span>
                </button>
            </div>

        </div>
    </div>


    {{-- ══════════════════════════════════════ --}}
    {{-- MODAL KONFIRMASI HAPUS                --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="showDeleteConfirm"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" @click="showDeleteConfirm = false"></div>

        <div class="relative w-full max-w-sm z-10 rounded-3xl p-6 text-center shadow-2xl"
             style="background: linear-gradient(135deg, #102318 0%, #0a160f 100%); border: 1px solid rgba(239,68,68,0.2);"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="w-14 h-14 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h4 class="font-display text-lg font-bold mb-1">Hapus Kegiatan?</h4>
            <p class="text-white/40 font-body text-sm mb-6 max-w-xs mx-auto">
                Kegiatan ini akan dihapus secara permanen dan tidak bisa dikembalikan.
            </p>

            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false; deleteTargetId = null"
                        class="flex-1 py-2.5 rounded-full border border-white/10 text-white/60 font-body text-sm hover:border-white/20 hover:text-white transition-all">
                    Batal
                </button>
                <button @click="confirmDelete()"
                        :disabled="submitting"
                        class="flex-1 py-2.5 rounded-full bg-red-500 text-white font-body font-medium text-sm hover:bg-red-400 transition-all disabled:opacity-60 flex items-center justify-center gap-2">
                    <svg x-show="submitting" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menghapus...' : 'Ya, Hapus'"></span>
                </button>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════ --}}
    {{-- TOAST NOTIFICATION                    --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="toast.show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         :class="toast.type === 'success'
             ? 'bg-green-500/15 border-green-500/25 text-green-400'
             : 'bg-red-500/15 border-red-500/25 text-red-400'"
         class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-3.5 rounded-2xl border font-body text-sm shadow-2xl"
         style="backdrop-filter: blur(20px);">
        <svg x-show="toast.type === 'success'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <svg x-show="toast.type === 'error'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span x-text="toast.message"></span>
    </div>

</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@push('scripts')
<script>
function planManager() {
    return {
        // ── Data dari server ───────────────────────
        plans: @json($destination->plans),
        destinationId: {{ $destination->id }},
        maxHari: {{ $destination->lama_hari }},
        tanggalBerangkat: '{{ \Carbon\Carbon::parse($destination->tanggal_berangkat)->format('Y-m-d') }}',
        csrfToken: '{{ csrf_token() }}',

        // ── UI state ───────────────────────────────
        showModal: false,
        showDeleteConfirm: false,
        isEditing: false,
        submitting: false,
        activeDay: null,
        deleteTargetId: null,
        errorMsg: '',

        // ── Form ───────────────────────────────────
        form: { hari: 1, jam: '07:00', kegiatan: '', lokasi: '' },
        editingId: null,

        // ── Toast ──────────────────────────────────
        toast: { show: false, message: '', type: 'success' },

        // ── Init ───────────────────────────────────
        init() { /* plans sudah di-pass dari Blade via @json */ },

        // ── Computed: group plans by hari ──────────
        get groupedPlans() {
            const map = {};
            this.plans.forEach(p => {
                if (!map[p.hari]) map[p.hari] = [];
                map[p.hari].push(p);
            });
            return Object.keys(map)
                .map(Number)
                .sort((a, b) => a - b)
                .map(hari => ({
                    hari,
                    items: map[hari].sort((a, b) => a.jam.localeCompare(b.jam)),
                }));
        },

        // ── Computed: filter by active day ─────────
        get filteredGroups() {
            if (this.activeDay === null) return this.groupedPlans;
            return this.groupedPlans.filter(g => g.hari === this.activeDay);
        },

        // ── Helpers ────────────────────────────────
        formatTime(time) {
            // "07:30:00" → "07:30"
            return time ? String(time).substring(0, 5) : '--:--';
        },

        getDayDate(hari) {
            if (!hari || hari < 1) return '';
            const d = new Date(this.tanggalBerangkat + 'T00:00:00');
            d.setDate(d.getDate() + (Number(hari) - 1));
            return d.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
            });
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3500);
        },

        resetForm() {
            this.form = { hari: 1, jam: '07:00', kegiatan: '', lokasi: '' };
            this.editingId = null;
            this.isEditing = false;
            this.errorMsg = '';
        },

        // ── Modal controls ─────────────────────────
        openAdd(hari = null) {
            this.resetForm();
            this.form.hari = hari ?? this.activeDay ?? 1;
            this.showModal = true;
        },

        openEdit(plan) {
            this.resetForm();
            this.isEditing = true;
            this.editingId = plan.id;
            this.form = {
                hari: plan.hari,
                jam: String(plan.jam).substring(0, 5),
                kegiatan: plan.kegiatan,
                lokasi: plan.lokasi ?? '',
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        },

        promptDelete(id) {
            this.deleteTargetId = id;
            this.showDeleteConfirm = true;
        },

        // ── STORE / UPDATE ─────────────────────────
        async submitForm() {
            this.errorMsg = '';

            // Validasi sisi client
            if (!this.form.hari || this.form.hari < 1 || this.form.hari > this.maxHari) {
                this.errorMsg = `Hari harus antara 1 sampai ${this.maxHari}.`;
                return;
            }
            if (!this.form.jam) {
                this.errorMsg = 'Jam kegiatan wajib diisi.';
                return;
            }
            if (!this.form.kegiatan.trim()) {
                this.errorMsg = 'Deskripsi kegiatan wajib diisi.';
                return;
            }

            this.submitting = true;
            const url    = this.isEditing ? `/plans/${this.editingId}` : '/plans';
            const method = this.isEditing ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        destination_id: this.destinationId,
                        hari:     this.form.hari,
                        jam:      this.form.jam,
                        kegiatan: this.form.kegiatan.trim(),
                        lokasi:   this.form.lokasi || null,
                    }),
                });

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    // Tampilkan error validasi dari Laravel
                    const msgs = data.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : (data.message || 'Terjadi kesalahan, coba lagi.');
                    this.errorMsg = msgs;
                    return;
                }

                if (this.isEditing) {
                    // Update local state
                    const idx = this.plans.findIndex(p => p.id === this.editingId);
                    if (idx !== -1) {
                        this.plans[idx] = {
                            ...this.plans[idx],
                            hari:     this.form.hari,
                            jam:      this.form.jam + ':00',
                            kegiatan: this.form.kegiatan.trim(),
                            lokasi:   this.form.lokasi || null,
                        };
                        // Trigger reactivity
                        this.plans = [...this.plans];
                    }
                    this.showToast('Kegiatan berhasil diperbarui! ✓');
                } else {
                    // Tambah ke local state — pakai data dari server jika tersedia
                    const newPlan = (data && data.id)
                        ? data
                        : {
                            id:             Date.now(),  // temp id
                            destination_id: this.destinationId,
                            hari:           this.form.hari,
                            jam:            this.form.jam + ':00',
                            kegiatan:       this.form.kegiatan.trim(),
                            lokasi:         this.form.lokasi || null,
                        };
                    this.plans = [...this.plans, newPlan];
                    this.showToast('Kegiatan berhasil ditambahkan! ✓');
                }

                this.closeModal();

            } catch (err) {
                this.errorMsg = 'Gagal terhubung ke server. Periksa koneksimu.';
            } finally {
                this.submitting = false;
            }
        },

        // ── DELETE ─────────────────────────────────
        async confirmDelete() {
            this.submitting = true;
            try {
                const res = await fetch(`/plans/${this.deleteTargetId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                });

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    this.showToast(data.message || 'Gagal menghapus kegiatan.', 'error');
                    return;
                }

                this.plans = this.plans.filter(p => p.id !== this.deleteTargetId);
                this.showDeleteConfirm = false;
                this.deleteTargetId = null;
                this.showToast('Kegiatan berhasil dihapus!');

            } catch (err) {
                this.showToast('Gagal terhubung ke server.', 'error');
            } finally {
                this.submitting = false;
            }
        },
    };
}
</script>
@endpush
