<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\PerkaraController;

// Public Routes
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/perkara', [PublicController::class, 'perkara'])->name('perkara.public');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perkara Management
    Route::resource('perkaras', PerkaraController::class);

    // Personel Management
    Route::resource('personels', \App\Http\Controllers\Admin\PersonelController::class);
});
