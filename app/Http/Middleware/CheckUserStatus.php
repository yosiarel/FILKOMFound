<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login terlebih dahulu.
        // Middleware 'auth' seharusnya sudah menangani ini, tapi bisa jadi lapisan keamanan tambahan.
        if (!Auth::check()) {
            return redirect()->route('login'); // Atau ke halaman lain jika tidak login
        }

        $user = Auth::user();

        // Contoh: Memeriksa apakah status pengguna adalah 'active'
        // Asumsi model User memiliki kolom 'status'
        if ($user->status === 'inactive' || $user->status === 'banned') {
            // Jika status tidak aktif atau diblokir, arahkan ke halaman error atau halaman informasi status
            return redirect()->route('user.inactive-account'); // Rute ini harus Anda definisikan
            // Atau Anda bisa menggunakan:
            // abort(403, 'Akun Anda tidak aktif atau diblokir.');
        }

        // Contoh lain: Memeriksa apakah email pengguna sudah diverifikasi
        // If (!$user->hasVerifiedEmail()) { // Metode ini ada jika Anda menggunakan fitur verifikasi email Laravel
        //     return redirect()->route('verification.notice');
        // }

        return $next($request); // Lanjutkan request jika status pengguna valid
    }
}