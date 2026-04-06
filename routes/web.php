<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;

// Landing Page
Route::get('/', function () {
    $semua_destinasi = \App\Models\Destination::latest()->get();
    return view('welcome', compact('semua_destinasi'));
})->name('welcome');


// Semua rute di dalam grup ini WAJIB login
Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD (Beranda)
    // Diarahkan ke fungsi dashboard() 
    Route::get('/dashboard', [DestinationController::class, 'dashboard'])->name('dashboard');


    //  DESTINASI LIBURAN (
    // otomatis membuat route: index (Lihat Semua), create (Tambah), show, dll
    Route::resource('destinations', DestinationController::class);


    // RENCANA PERJALANAN (Plans)
    // A. Nested resource untuk nambah/edit rencana di dalam sebuah destinasi
    Route::resource('destinations.plans', PlanController::class)
         ->only(['create', 'store', 'edit', 'update', 'destroy']);
    
    // Route khusus untuk tombol "Rencana Perjalanan" di Sidebar 
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');


    // 5. PROFIL BREEZE 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Auth routes (Laravel Breeze)
require __DIR__.'/auth.php';