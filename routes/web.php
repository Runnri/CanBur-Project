<?php
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $semua_destinasi = \App\Models\Destination::all(); // Mengambil data untuk halaman depan
    return view('welcome', compact('semua_destinasi'));
});

// route crud
Route::resource('destinations', DestinationController::class);
Route::resource('plans', PlanController::class)->only(['store','update','destroy']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/', fn() => view('welcome'))->name('welcome');


Route::get('/dashboard', [DestinationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('/destinations', DestinationController::class)
    ->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
