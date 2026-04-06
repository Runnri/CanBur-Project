<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;

// 1. Halaman Depan / Landing Page
Route::get('/', function () {
    $semua_destinasi = \App\Models\Destination::latest()->get();
    return view('welcome', compact('semua_destinasi'));
})->name('welcome');


// Semua rute di dalam grup ini WAJIB login
Route::middleware(['auth', 'verified'])->group(function () {

    // 2. DASHBOARD (Beranda)
    // Diarahkan ke fungsi dashboard() yang baru saja kita pisah
    Route::get('/dashboard', [DestinationController::class, 'dashboard'])->name('dashboard');


    // 3. DESTINASI LIBURAN (CRUD Full)
    // Ini otomatis membuat route: index (Lihat Semua), create (Tambah), show, dll
    Route::resource('destinations', DestinationController::class);


    // 4. RENCANA PERJALANAN (Plans)
    // A. Nested resource untuk nambah/edit rencana di dalam sebuah destinasi
    Route::resource('destinations.plans', PlanController::class)
         ->only(['create', 'store', 'edit', 'update', 'destroy']);
    
    // B. Route khusus untuk tombol "Rencana Perjalanan" di Sidebar (Panah Biru)
    // Pastikan di PlanController kamu nanti ada public function index()
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');


    // 5. PROFIL BREEZE (Panah Abu-abu)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Auth routes (Laravel Breeze)
require __DIR__.'/auth.php';