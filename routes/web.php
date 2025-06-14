<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ItemController as UserItemController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\ClaimController as UserClaimController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
// <-- TAMBAHAN: use statement untuk controller pengumuman user -->
use App\Http\Controllers\User\AnnouncementController as UserAnnouncementController; 
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AnnouncementController; // Ini untuk Admin, sudah benar


/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
| Rute yang dapat diakses oleh semua pengguna (tidak terotentikasi).
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Grup rute untuk pengguna tamu saja (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rute logout, dapat diakses oleh pengguna yang sudah terotentikasi
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Mahasiswa)
|--------------------------------------------------------------------------
| Rute untuk pengguna yang terotentikasi dengan peran 'mahasiswa'.
*/
Route::middleware(['auth', 'checkRole:mahasiswa'])->prefix('user')->name('user.')->group(function () {
    // Dashboard untuk mahasiswa
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Items (Barang Temuan)
    Route::get('/items', [UserItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [UserItemController::class, 'create'])->name('items.create');
    Route::post('/items', [UserItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [UserItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [UserItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [UserItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [UserItemController::class, 'destroy'])->name('items.destroy');

    // <-- TAMBAHAN: Blok rute baru untuk Pengumuman Barang Hilang -->
    // Announcements (Barang Hilang)
    Route::get('/announcements', [UserAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [UserAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [UserAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}', [UserAnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{announcement}/edit', [UserAnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [UserAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [UserAnnouncementController::class, 'destroy'])->name('announcements.destroy');

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
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Rute untuk pengguna dengan peran 'admin'.
*/
Route::middleware(['auth', 'checkRole:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard untuk admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verification
    Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{id}', [VerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

    // Announcements (Admin)
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});

/*
|--------------------------------------------------------------------------
| General Authenticated Routes (Fallback/Redirect)
|--------------------------------------------------------------------------
| Rute ini berfungsi sebagai penanganan umum untuk pengguna yang terotentikasi.
*/
Route::middleware('auth')->group(function () {
    // Redirect berdasarkan peran setelah login
    Route::get('/dashboard', function () {
        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) { // Asumsi ada method hasRole() di model User
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->hasRole('mahasiswa')) {
                return redirect()->route('user.dashboard');
            }
            return view('beranda');
        }
        return redirect()->route('login');
    })->name('dashboard');
});