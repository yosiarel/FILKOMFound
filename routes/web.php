<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
// User Controllers
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ItemController;
use App\Http\Controllers\User\AnnouncementController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ClaimController;
// Admin Controllers
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\ClaimController as AdminClaimController;
use App\Http\Controllers\Admin\AnnouncementReportController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Untuk Tamu / Pengguna yang Belum Login)
|--------------------------------------------------------------------------
*/

// Rute ini bisa diakses siapa saja, termasuk yang belum login.
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Rute ini hanya untuk tamu. Jika sudah login, akan diarahkan ke dashboard.
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Untuk Pengguna yang Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Routes (for both roles)
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/lost-items-history', [ProfileController::class, 'announcementsHistory'])->name('profile.announcementshistory');
        Route::get('/profile/found-items-history', [ProfileController::class, 'foundItemsHistory'])->name('profile.foundhistory');
        Route::get('/profile/claimed-items-history', [ProfileController::class, 'claimedItemsHistory'])->name('profile.claimedhistory');

        // Item and Announcement Resources
        Route::resource('items', ItemController::class);
        Route::resource('announcements', AnnouncementController::class);
        
        // Mahasiswa-specific actions
        Route::middleware('checkRole:mahasiswa')->group(function () {
            Route::post('/claims/{item}', [ClaimController::class, 'store'])->name('claims.store');
            Route::post('/announcements/{announcement}/report', [AnnouncementController::class, 'report'])->name('announcements.report');
        });

        // Admin-specific actions
        Route::middleware('checkRole:admin')->prefix('admin')->name('admin.')->group(function () {
            
            Route::get('/items/{item}/detail', [VerificationController::class, 'showUnifiedDetail'])->name('items.show_detail');

            // Initial Found Item Verification
            Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
            Route::get('/verifications/{item}', [VerificationController::class, 'show'])->name('verifications.show');
            Route::post('/verifications/{item}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
            Route::post('/verifications/{item}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

            // Claim Verification & Handover
            Route::get('/claims', [AdminClaimController::class, 'index'])->name('claims.index');
            Route::get('/claims/history', [AdminClaimController::class, 'history'])->name('claims.history');
            Route::get('/claims/{item}', [AdminClaimController::class, 'show'])->name('claims.show');
            Route::post('/claims/{claim}/handover', [AdminClaimController::class, 'handover'])->name('claims.handover');

            // Announcement Reports
            Route::get('/reports/announcements', [AnnouncementReportController::class, 'index'])->name('reports.announcements.index');
            Route::post('/reports/{report}/resolve', [AnnouncementReportController::class, 'resolveReport'])->name('reports.resolve');
            Route::delete('/reports/announcements/{announcement}', [AnnouncementReportController::class, 'destroyAnnouncement'])->name('reports.announcements.destroy');
        });
    });
});