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
| Middleware 'auth' akan memastikan pengguna sudah login.
| Middleware 'role:mahasiswa' akan memastikan pengguna memiliki peran 'mahasiswa'.
| Middleware 'check.nim' dan 'check.user.status' akan dijalankan setelah otentikasi
| dan pengecekan peran untuk validasi tambahan.
*/
Route::middleware(['auth', 'checkRole', 'check.nim', 'check.user.status'])->prefix('user')->name('user.')->group(function () {
    // Dashboard untuk mahasiswa
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Menghapus rute '/beranda' yang redundant jika '/dashboard' sudah menampilkan halaman utama user

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

    // Logout (opsional, sudah ada global logout, tapi bisa saja ada spesifik user logout)
    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Rute untuk pengguna dengan peran 'admin'.
| Middleware 'auth' akan memastikan pengguna sudah login.
| Middleware 'role:admin' akan memastikan pengguna memiliki peran 'admin'.
| Middleware 'admin.only' (jika ada logika tambahan) akan dijalankan.
*/
Route::middleware(['auth', 'role:admin', 'admin.only'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard untuk admin
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

/*
|--------------------------------------------------------------------------
| General Authenticated Routes (Fallback/Redirect)
|--------------------------------------------------------------------------
| Rute ini berfungsi sebagai penanganan umum untuk pengguna yang terotentikasi.
| Jika ada peran khusus yang belum ditangani (misal: bukan mahasiswa/admin),
| atau untuk landing page setelah login sebelum redirect ke dashboard spesifik.
*/
Route::middleware('auth')->group(function () {
    // Jika tidak ada peran spesifik yang sesuai, arahkan ke dashboard user secara default
    // Anda bisa menambahkan logika redirect di sini berdasarkan peran pengguna
    Route::get('/dashboard', function () {
        // Contoh: Redirect berdasarkan peran setelah login
        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) { // Asumsi ada method hasRole() di model User
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->hasRole('mahasiswa')) {
                return redirect()->route('user.dashboard');
            }
            // Jika ada peran lain atau tidak ada peran yang cocok, bisa ke halaman default
            return view('beranda'); // Atau halaman default lainnya
        }
        return redirect()->route('login');
    })->name('dashboard'); // Nama rute ini bisa 'dashboard' saja, tanpa 'user.'

    // Jika Anda ingin semua pengguna yang terotentikasi melihat halaman 'beranda' sebagai default
    // dan kemudian rute role-specific akan diakses setelahnya, ini bisa dipertimbangkan.
    // Route::get('/beranda', fn () => view('beranda'))->name('beranda');
});