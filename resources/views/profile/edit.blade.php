@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page-title', 'Profil Pengguna')
@section('page-subtitle', 'Kelola informasi akun dan kata sandi kamu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-10">

    {{-- 1. FORM UBAH INFORMASI PROFIL (Nama & Email) --}}
    <div class="card rounded-3xl p-6 md:p-8 bg-forest-800/50 border border-white/5">
        <header class="mb-6">
            <h2 class="text-lg font-display font-bold text-sand-400 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-sand-400/20 flex items-center justify-center text-sand-400 text-sm">👤</span>
                Informasi Profil
            </h2>
            <p class="mt-1 text-sm text-white/50 font-body">
                Perbarui nama akun kamu di sini. Alamat email tidak dapat diubah.
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            {{-- Input Nama --}}
            <div class="space-y-1.5">
                <label class="text-xs font-body text-white/50 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                       class="w-full px-4 py-3 rounded-xl font-body text-sm bg-forest-900/50 border border-white/10 text-white/80 focus:outline-none focus:border-sand-400/50 focus:ring-1 focus:ring-sand-400/50 transition-all">
                @error('name') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
            </div>

            {{-- Input Email (Terkunci / Readonly) --}}
            <div class="space-y-1.5">
                <label class="text-xs font-body text-white/50 uppercase tracking-wider">Alamat Email <span class="text-white/20 lowercase">(tidak dapat diubah)</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required readonly
                       class="w-full px-4 py-3 rounded-xl font-body text-sm bg-forest-900/20 border border-white/5 text-white/40 cursor-not-allowed focus:outline-none transition-all">
                @error('email') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol Simpan Profil --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-forest-900/80 border border-white/10 text-white font-body font-bold text-sm hover:border-sand-400/50 hover:bg-forest-800 transition-all hover:shadow-lg hover:shadow-sand-400/10 hover:-translate-y-0.5">
                    Simpan Profil
                </button>
                
                {{-- Animasi tulisan "Tersimpan" saat berhasil --}}
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-400 font-body flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tersimpan.
                    </p>
                @endif
            </div>
        </form>
    </div>

    {{-- 2. FORM UBAH KATA SANDI --}}
    <div class="card rounded-3xl p-6 md:p-8 bg-forest-800/50 border border-white/5">
        <header class="mb-6">
            <h2 class="text-lg font-display font-bold text-sand-400 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-sand-400/20 flex items-center justify-center text-sand-400 text-sm">🔒</span>
                Perbarui Kata Sandi
            </h2>
            <p class="mt-1 text-sm text-white/50 font-body">
                Pastikan akun kamu menggunakan kata sandi yang panjang dan kuat agar tetap aman.
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            {{-- Kata Sandi Lama --}}
            <div class="space-y-1.5">
                <label class="text-xs font-body text-white/50 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" autocomplete="current-password"
                       class="w-full px-4 py-3 rounded-xl font-body text-sm bg-forest-900/50 border border-white/10 text-white/80 focus:outline-none focus:border-sand-400/50 focus:ring-1 focus:ring-sand-400/50 transition-all">
                @error('current_password', 'updatePassword') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
            </div>

            {{-- Kata Sandi Baru --}}
            <div class="space-y-1.5">
                <label class="text-xs font-body text-white/50 uppercase tracking-wider">Kata Sandi Baru</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="w-full px-4 py-3 rounded-xl font-body text-sm bg-forest-900/50 border border-white/10 text-white/80 focus:outline-none focus:border-sand-400/50 focus:ring-1 focus:ring-sand-400/50 transition-all">
                @error('password', 'updatePassword') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
            </div>

            {{-- Konfirmasi Kata Sandi Baru --}}
            <div class="space-y-1.5">
                <label class="text-xs font-body text-white/50 uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full px-4 py-3 rounded-xl font-body text-sm bg-forest-900/50 border border-white/10 text-white/80 focus:outline-none focus:border-sand-400/50 focus:ring-1 focus:ring-sand-400/50 transition-all">
                @error('password_confirmation', 'updatePassword') <p class="text-red-400 text-xs font-body">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol Simpan Sandi --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-full bg-forest-900/80 border border-white/10 text-white font-body font-bold text-sm hover:border-sand-400/50 hover:bg-forest-800 transition-all hover:shadow-lg hover:shadow-sand-400/10 hover:-translate-y-0.5">
                    Ubah Kata Sandi
                </button>

                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-400 font-body flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tersimpan.
                    </p>
                @endif
            </div>
        </form>
    </div>

</div>
@endsection