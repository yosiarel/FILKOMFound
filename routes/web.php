<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ItemController as UserItemController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\ClaimController as UserClaimController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
// TAMBAHAN: use statement untuk controller pengumuman user umum (jika ada)
use App\Http\Controllers\User\AnnouncementController as UserAnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController; // Namakan ulang agar jelas ini untuk admin

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
// Hapus atau hati-hati dengan rute GET untuk logout. Ini bisa menjadi celah keamanan CSRF.
// Umumnya logout hanya POST. Jika Anda benar-benar butuh GET, pastikan ada validasi ekstra.
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get'); 

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Mahasiswa)
|--------------------------------------------------------------------------
| Rute untuk pengguna yang terotentikasi dengan peran 'mahasiswa'.
*/
Route::middleware(['auth', 'checkRole:mahasiswa'])->prefix('user')->name('user.')->group(function () {
    // Dashboard untuk mahasiswa
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Items (Barang Temuan) - Ini untuk manajemen barang temuan secara umum oleh user (misal user submit barang temuan)
    Route::get('/items', [UserItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [UserItemController::class, 'create'])->name('items.create');
    Route::post('/items', [UserItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [UserItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [UserItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [UserItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [UserItemController::class, 'destroy'])->name('items.destroy');

    // Announcements (Barang Hilang) - Ini untuk manajemen pengumuman barang hilang secara umum oleh user (misal user submit laporan kehilangan)
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

    // Profile (Rute utama profil)
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // =====================================================================
    //           *** RUTE BARU UNTUK HISTORI DI HALAMAN PROFIL ***
    // =====================================================================
    Route::get('/profile/lost-items', [UserProfileController::class, 'announcements'])->name('profile.announcements'); // Menggunakan nama method 'announcements' di UserProfileController
    Route::get('/profile/found-items', [UserProfileController::class, 'foundItems'])->name('profile.found-items');
    Route::get('/profile/claimed-items', [UserProfileController::class, 'claimedItems'])->name('profile.claimed-items');
    // =====================================================================
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
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index'); // Gunakan AdminAnnouncementController
    Route::get('/announcements/create', [AdminAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AdminAnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
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
            // Jika tidak ada peran spesifik atau peran tidak dikenali, arahkan ke landing page
            return redirect()->route('landing'); // atau ke route lain yang sesuai
        }
        return redirect()->route('login');
    })->name('dashboard');
});