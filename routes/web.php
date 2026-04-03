<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ================================
// Halaman Home / Welcome
// ================================
Route::get('/', function () {
    $semua_destinasi = \App\Models\Destination::all();
    return view('welcome', compact('semua_destinasi'));
})->name('welcome');

// ================================
// Dashboard (hanya untuk user terverifikasi)
// ================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ================================
// CRUD Destinations (hanya untuk user login)
// ================================
Route::middleware(['auth'])->group(function () {
    Route::resource('destinations', DestinationController::class);
});

// ================================
// CRUD Plans (hanya untuk user login, terbatas store, update, destroy)
// ================================
Route::middleware(['auth'])->group(function () {
    Route::resource('plans', PlanController::class)
        ->only(['store', 'update', 'destroy']);
});

// ================================
// Profile (hanya untuk user login)
// ================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================================
// Auth routes (Laravel Breeze)
// ================================
require __DIR__.'/auth.php';