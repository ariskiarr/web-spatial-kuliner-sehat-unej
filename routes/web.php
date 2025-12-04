<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TempatMakanController;
use App\Http\Controllers\Admin\MenuMakanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tempat-makan', TempatMakanController::class);
    
    // Menu Makan Routes (nested under tempat-makan)
    Route::get('tempat-makan/{tempat_id}/menu', [MenuMakanController::class, 'index'])->name('menu-makan.index');
    Route::get('tempat-makan/{tempat_id}/menu/create', [MenuMakanController::class, 'create'])->name('menu-makan.create');
    Route::post('tempat-makan/{tempat_id}/menu', [MenuMakanController::class, 'store'])->name('menu-makan.store');
    Route::get('tempat-makan/{tempat_id}/menu/{id}/edit', [MenuMakanController::class, 'edit'])->name('menu-makan.edit');
    Route::put('tempat-makan/{tempat_id}/menu/{id}', [MenuMakanController::class, 'update'])->name('menu-makan.update');
    Route::delete('tempat-makan/{tempat_id}/menu/{id}', [MenuMakanController::class, 'destroy'])->name('menu-makan.destroy');
    
    // API for calorie estimation
    Route::post('menu/estimate-kalori', [MenuMakanController::class, 'getCalorieEstimate'])->name('menu.estimate-kalori');
});

require __DIR__.'/auth.php';
