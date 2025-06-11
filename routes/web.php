<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ItemController as UserItemController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\ClaimController as UserClaimController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    // Auth
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Items
    Route::get('/items', [UserItemController::class, 'index'])->name('items.index');
    Route::get('/items/{id}', [UserItemController::class, 'show'])->name('items.show');

    // Reports
    Route::get('/reports/create', [UserReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [UserReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/history', [UserReportController::class, 'history'])->name('reports.history');

    // Claims
    Route::post('/claims/{itemId}', [UserClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/history', [UserClaimController::class, 'history'])->name('claims.history');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verification
    Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{id}', [VerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});
