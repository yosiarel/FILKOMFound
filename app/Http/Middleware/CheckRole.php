<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array<string>  $roles  Daftar peran yang diizinkan untuk mengakses rute.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect('login');
        }
        
        // 2. Periksa apakah peran pengguna ada di dalam daftar peran ($roles) yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            // Jika peran pengguna tidak ada dalam daftar yang diizinkan, tolak akses.
            abort(403, 'AKSES DITOLAK.');
        }

        // Jika semua kondisi terpenuhi, lanjutkan request
        return $next($request);
    }
}