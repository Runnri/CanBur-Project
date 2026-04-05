<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ================================
// Home / Welcome
// ================================
Route::get('/', function () {
    $semua_destinasi = \App\Models\Destination::all();
    return view('welcome', compact('semua_destinasi'));
})->name('welcome');


// ================================
// SEMUA YANG BUTUH LOGIN
// ================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ============================
    // DESTINATIONS (FULL CRUD)
    // ============================
    Route::resource('destinations', DestinationController::class);

    // ============================
    // PLANS (LIMITED CRUD)
    // ============================
    Route::resource('plans', PlanController::class)
        ->only(['store', 'update', 'destroy']);

    // ============================
    // PROFILE
    // ============================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// ================================
// AUTH (Breeze / Laravel default)
// ================================
require __DIR__.'/auth.php';