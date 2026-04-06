@extends('layouts.app')

@section('title', 'Tambah Destinasi')
@section('page-title', 'Tambah Destinasi')
@section('page-subtitle', 'Catat destinasi liburan impianmu')

@section('header-actions')
    <a href="{{ route('destinations.index') }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 text-white/60 font-body text-sm hover:border-white/20 hover:text-white transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">
    <form action="{{ route('destinations.store') }}" method="POST" enctype="multipart/form-data"
          x-data="destinationForm()" autocomplete="off">
        @csrf

        
        <div class="space-y-5">

            <div class="card rounded-3xl p-6 bg-forest-800/50">
    <h3 class="font-display text-base font-bold mb-4 flex items-center gap-2">
        <span class="w-6 h-6 rounded-lg bg-sand-400/20 text-sand-400 flex items-center justify-center text-xs">01</span>
        Foto Destinasi
    </h3>
    
    {{-- Alpine.js murni HANYA untuk nangkep URL gambar, ga ganggu file --}}
    <div x-data="{ imageUrl: null }" class="relative mt-2">
        
        <div class="border-2 border-dashed border-white/20 bg-forest-900/30 rounded-2xl flex flex-col items-center justify-center relative overflow-hidden transition-all duration-300 h-64 hover:border-sand-400/50">
            
            {{-- 1. Tampilan KOSONG --}}
            <div x-show="!imageUrl" class="text-center z-10 pointer-events-none p-6">
                <svg class="w-12 h-12 text-white/20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <p class="text-sm font-bold text-white/70 mb-1">Klik untuk pilih foto</p>
            </div>

            {{-- 2. Tampilan PREVIEW --}}
            <img x-show="imageUrl" :src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10" x-cloak>
            
            {{-- 3. INPUT ASLI (Menutupi seluruh kotak, transparan) --}}
            <input type="file" 
                   name="foto" 
                   accept="image/*"
                   @change="imageUrl = URL.createObjectURL($event.target.files[0])"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                   required> {{-- Tambahkan required buat ngetes form ga bisa disubmit kalau kosong --}}
        </div>

        @error('foto')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>
</div>

            {{-- Main info --}}
            <div class="card rounded-3xl p-6 space-y-5">
                <h3 class="font-display text-base font-bold flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sand-400/20 flex items-center justify-center text-sand-400 text-xs">✏️</span>
                    Informasi Destinasi
                </h3>

                {{-- Judul --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">Judul Liburan *</label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           placeholder="Contoh: Trip Bromo 2025"
                           class="input-field w-full px-4 py-3 rounded-xl font-body text-sm @error('judul') border-red-500/50 @enderror">
                    @error('judul') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal & Lama --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-body text-white/50 uppercase tracking-wider">Tanggal Keberangkatan *</label>
                        <input type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat') }}"
                               class="input-field w-full px-4 py-3 rounded-xl font-body text-sm @error('tanggal_berangkat') border-red-500/50 @enderror"
                               style="color-scheme: dark;">
                        @error('tanggal_berangkat') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-body text-white/50 uppercase tracking-wider">Lama Perjalanan (Hari) *</label>
                        <input type="number" name="lama_hari" value="{{ old('lama_hari', 1) }}" min="1"
                               placeholder="3"
                               class="input-field w-full px-4 py-3 rounded-xl font-body text-sm @error('lama_hari') border-red-500/50 @enderror">
                        @error('lama_hari') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Budget --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">Budget (Rp) *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30 font-body text-sm">Rp</span>
                        <input type="number" name="budget" value="{{ old('budget') }}"
                               placeholder="2500000"
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl font-body text-sm @error('budget') border-red-500/50 @enderror">
                    </div>
                    @error('budget') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">Status</label>
                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="0" class="hidden peer" {{ old('status', '0') == '0' ? 'checked' : '' }}>
                            <div class="peer-checked:border-sand-400 peer-checked:bg-sand-400/10 peer-checked:text-sand-400 border border-white/10 rounded-xl p-3 text-center font-body text-sm text-white/50 transition-all duration-200 hover:border-white/20">
                                ⏳ Belum Tercapai
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="1" class="hidden peer" {{ old('status') == '1' ? 'checked' : '' }}>
                            <div class="peer-checked:border-green-500 peer-checked:bg-green-500/10 peer-checked:text-green-400 border border-white/10 rounded-xl p-3 text-center font-body text-sm text-white/50 transition-all duration-200 hover:border-white/20">
                                ✅ Sudah Tercapai
                            </div>
                        </label>
                    </div>
                    @error('status') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('destinations.index') }}" class="text-white/40 font-body text-sm hover:text-white transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-8 py-3 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-xl hover:shadow-sand-400/20 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Destinasi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function destinationForm() {
    return {
        preview: null,
        dragging: false,
        handleFile(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.preview = URL.createObjectURL(file);
            }
        },
        handleDrop(event) {
            event.preventDefault();
            this.dragging = false;

            const files = event.dataTransfer.files;
            if (!files.length) return;

            const file = files[0];
            if (file.type.startsWith('image/')) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.fileInput.files = dataTransfer.files;

                this.preview = URL.createObjectURL(file);
            }
        }
    }
}
</script>

@endpush