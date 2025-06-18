<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
// Kita akan gunakan SATU set controller untuk halaman yang sama
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ItemController;
use App\Http\Controllers\User\AnnouncementController;
use App\Http\Controllers\User\ProfileController;
// Controller khusus Admin
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\User\ClaimController;


/*
|--------------------------------------------------------------------------
| Rute Publik (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});


/*
|--------------------------------------------------------------------------
| Rute Terotentikasi (Untuk SEMUA Peran yang Diizinkan)
|--------------------------------------------------------------------------
*/

// Logout bisa diakses siapa saja yang sudah login
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// GRUP UTAMA: Dapat diakses oleh 'admin' DAN 'mahasiswa'
Route::middleware(['auth', 'checkRole:admin,mahasiswa'])->name('user.')->group(function () {

    // Dashboard utama untuk kedua peran
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utama untuk kedua peran
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // RUTE-RUTE BARU UNTUK HISTORY
    Route::get('/profile/lost-items', [ProfileController::class, 'announcementsHistory'])->name('profile.announcementshistory');
    Route::get('/profile/found-items', [ProfileController::class, 'foundItemsHistory'])->name('profile.foundhistory');
    Route::get('/profile/claimed-items', [ProfileController::class, 'claimedItemsHistory'])->name('profile.claimedhistory');

    // Resource untuk Items (Barang Temuan)
    Route::resource('items', ItemController::class);

    // Resource untuk Announcements (Barang Hilang)
    Route::resource('announcements', AnnouncementController::class);
    Route::post('/claims/{item}', [ClaimController::class, 'store'])->name('claims.store');


    /*
    |----------------------------------------------------------------------
    | Rute Khusus Admin (di dalam grup utama)
    |----------------------------------------------------------------------
    | Halaman ini hanya dapat diakses oleh pengguna dengan peran 'admin'.
    */
    Route::middleware('checkRole:admin')->prefix('admin-tasks')->name('admin.')->group(function () {
        
        Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{id}', [VerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');
        
        // Anda bisa menambahkan rute khusus admin lainnya di sini
        // Contoh: Route::get('/manage-users', [ManageUsersController::class, 'index'])->name('users.index');
    });

});