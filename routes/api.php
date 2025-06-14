<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ItemController as UserItemController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\ClaimController as UserClaimController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute API untuk aplikasi Anda. Rute-rute ini
| secara otomatis dimuat oleh RouteServiceProvider dan diberi grup middleware "api".
| Nikmati membangun API Anda!
|
*/

// Contoh rute yang bisa digunakan untuk menguji autentikasi API
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Authenticated User API Routes (Mahasiswa)
|--------------------------------------------------------------------------
| Rute API untuk pengguna yang terotentikasi dengan peran 'mahasiswa'.
| Middleware 'auth:sanctum' digunakan untuk autentikasi API (token based).
| Middleware kustom seperti 'role:mahasiswa', 'check.nim', dan 'check.user.status'
| akan tetap diterapkan untuk otorisasi dan validasi tambahan.
*/
Route::middleware(['auth:sanctum', 'role:mahasiswa', 'check.nim', 'check.user.status'])->prefix('user')->name('api.user.')->group(function () {
    // Items API
    // Pastikan ini adalah endpoint API yang mengembalikan data, bukan tampilan.
    Route::get('/items', [UserItemController::class, 'index'])->name('items.index');
    Route::get('/items/{id}', [UserItemController::class, 'show'])->name('items.show');

    // Reports API
    // Perhatikan bahwa 'create' biasanya untuk form, mungkin lebih cocok di web.php
    // Namun, jika ini adalah endpoint untuk mendapatkan data form (misal: daftar pilihan), bisa di sini.
    Route::post('/reports', [UserReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/history', [UserReportController::class, 'history'])->name('reports.history');

    // Claims API
    Route::post('/claims/{itemId}', [UserClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/history', [UserClaimController::class, 'history'])->name('claims.history');

    // Profile API (untuk pembaruan data profil)
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index'); // Untuk mengambil data profil
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
| Rute API untuk pengguna dengan peran 'admin'.
| Menggunakan 'auth:sanctum' untuk autentikasi API.
| Middleware 'role:admin' dan 'admin.only' diterapkan untuk otorisasi.
*/
Route::middleware(['auth:sanctum', 'role:admin', 'admin.only'])->prefix('admin')->name('api.admin.')->group(function () {
    // Verification API
    Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{id}', [VerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

    // Announcements API
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    // Jika 'create' adalah endpoint untuk mendapatkan data form, bisa di sini.
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit'); // Untuk mengambil data pengumuman untuk edit
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});
