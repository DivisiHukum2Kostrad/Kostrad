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
    Route::get('/perkaras/export-excel', [PerkaraController::class, 'exportExcel'])->name('perkaras.export.excel');
    Route::get('/perkaras/export-pdf', [PerkaraController::class, 'exportPdf'])->name('perkaras.export.pdf');
    Route::resource('perkaras', PerkaraController::class);

    // Personel Management
    Route::resource('personels', \App\Http\Controllers\Admin\PersonelController::class);

    // Document Management
    Route::prefix('perkaras/{perkara}')->group(function () {
        Route::get('/documents', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'create'])->name('documents.create');
        Route::post('/documents', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'store'])->name('documents.store');
    });
    
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/{document}', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'show'])->name('show');
        Route::get('/{document}/edit', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'edit'])->name('edit');
        Route::put('/{document}', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'update'])->name('update');
        Route::post('/{document}/version', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'uploadVersion'])->name('uploadVersion');
        Route::get('/{document}/download', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'download'])->name('download');
        Route::get('/{document}/preview', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'preview'])->name('preview');
        Route::delete('/{document}', [\App\Http\Controllers\Admin\DokumenPerkaraController::class, 'destroy'])->name('destroy');
    });

    // User Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });

    // Activity Logs (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [\App\Http\Controllers\Admin\NotificationController::class, 'unread'])->name('unread');
        Route::post('/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/preferences', [\App\Http\Controllers\Admin\NotificationController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [\App\Http\Controllers\Admin\NotificationController::class, 'updatePreferences'])->name('updatePreferences');
    });

    // Batch File Operations
    Route::prefix('batch-operations')->name('batch.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BatchFileController::class, 'index'])->name('index');
        Route::post('/thumbnails', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchGenerateThumbnails'])->name('thumbnails');
        Route::post('/sign', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchSignDocuments'])->name('sign');
        Route::post('/delete', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchDeleteDocuments'])->name('delete');
        Route::post('/download', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchDownloadDocuments'])->name('download');
        Route::post('/qrcodes', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchGenerateQRCodes'])->name('qrcodes');
        Route::post('/qrcodes/cases', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchGenerateCaseQRCodes'])->name('qrcodes.cases');
        Route::post('/move', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchMoveDocuments'])->name('move');
        Route::post('/category', [\App\Http\Controllers\Admin\BatchFileController::class, 'batchUpdateCategory'])->name('category');
    });

    // File Management
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/{id}/thumbnail', [\App\Http\Controllers\Admin\BatchFileController::class, 'getThumbnail'])->name('thumbnail');
        Route::get('/{id}/qrcode', [\App\Http\Controllers\Admin\BatchFileController::class, 'getQRCode'])->name('qrcode');
        Route::post('/{id}/sign', [\App\Http\Controllers\Admin\BatchFileController::class, 'signDocument'])->name('sign');
        Route::get('/{id}/verify', [\App\Http\Controllers\Admin\BatchFileController::class, 'verifySignature'])->name('verify');
        Route::get('/{id}/metadata', [\App\Http\Controllers\Admin\BatchFileController::class, 'getMetadata'])->name('metadata');
    });
});

// Public Tracking Routes
Route::get('/track/case/{id}', [PublicController::class, 'trackCase'])->name('perkara.track');
Route::get('/track/document/{id}', [PublicController::class, 'trackDocument'])->name('dokumen.track');
