<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     * Use this for middleware that truly needs to run for ALL requests,
     * like handling maintenance mode or preparing strings.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // \App\Http\Middleware\ForceHttps::class, // Contoh middleware global opsional
    ];

    /**
     * The application's route middleware groups.
     *
     * These middleware groups are applied to web and API routes.
     * Custom authentication-dependent middleware can often be placed here.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Opsional: jika Anda menggunakan otentikasi berbasis sesi
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Middleware kustom yang bergantung pada sesi atau otentikasi pengguna
            // dan perlu dijalankan di seluruh grup 'web' (misal, setelah otentikasi berhasil)
            // Pastikan ini berjalan setelah \App\Http\Middleware\Authenticate jika diterapkan di rute web
            // Jika Anda ingin ini berjalan untuk setiap rute yang dilindungi oleh 'auth',
            // lebih baik definisikan alias di $routeMiddleware dan terapkan di rute.
            // \App\Http\Middleware\CheckNIM::class,
            // \App\Http\Middleware\CheckRole::class,
            // \App\Http\Middleware\CheckUserStatus::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Middleware kustom untuk API, jika perlu setelah otentikasi API
            // \App\Http\Middleware\YourCustomApiMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or individual routes.
     * This is the ideal place for most custom authorization/role-based middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // Menambahkan middleware kustom Anda di sini dengan alias
        'check.nim' => \App\Http\Middleware\CheckNIM::class,
        'check.role' => \App\Http\Middleware\CheckRole::class,
        // Mengoreksi namespace untuk CheckUserStatus (seharusnya 'App', bukan 'APP')
        'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
        'admin.only' => \App\Http\Middleware\AdminOnly::class,
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of the full class name to conveniently
     * assign middleware to routes or groups.
     *
     * @var array
     */
    protected $middlewareAliases = [
        // 'auth' sudah didefinisikan di $routeMiddleware, jadi tidak perlu lagi di sini
    ];
}