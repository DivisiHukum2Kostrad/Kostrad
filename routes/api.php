<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DokumenPerkaraController;
use App\Http\Controllers\Api\PerkaraController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // Cases (Perkara)
    Route::get('/perkaras', [PerkaraController::class, 'index']);
    Route::post('/perkaras', [PerkaraController::class, 'store']);
    Route::get('/perkaras/statistics', [PerkaraController::class, 'statistics']);
    Route::get('/perkaras/{perkara}', [PerkaraController::class, 'show']);
    Route::put('/perkaras/{perkara}', [PerkaraController::class, 'update']);
    Route::delete('/perkaras/{perkara}', [PerkaraController::class, 'destroy']);

    // Documents for a specific case
    Route::get('/perkaras/{perkara}/documents', [DokumenPerkaraController::class, 'index']);
    Route::post('/perkaras/{perkara}/documents', [DokumenPerkaraController::class, 'store']);
    
    // Document operations
    Route::get('/documents/{dokumen}', [DokumenPerkaraController::class, 'show']);
    Route::put('/documents/{dokumen}', [DokumenPerkaraController::class, 'update']);
    Route::delete('/documents/{dokumen}', [DokumenPerkaraController::class, 'destroy']);
    Route::get('/documents/{dokumen}/download', [DokumenPerkaraController::class, 'download']);
});
