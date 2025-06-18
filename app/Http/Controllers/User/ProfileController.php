<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Announcement; // <-- Pastikan ini di-import
use App\Models\Item;       // <-- Pastikan ini di-import
use App\Models\Claim;      // <-- Pastikan ini di-import

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman utama profil pengguna.
     */
    public function index()
    {
        // Langsung arahkan ke view halaman profil
        return view('profile.index');
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan validasi email mengabaikan email milik user itu sendiri
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan riwayat laporan kehilangan (announcements) milik pengguna.
     */
    public function announcementsHistory()
    {
        $user = Auth::user();
        // Mengambil dari model Announcement, bukan Item
        $announcements = Announcement::where('user_id', $user->id)
                                    ->latest()
                                    ->paginate(10);

        // Anda perlu membuat view ini: resources/views/profile/announcements/history.blade.php
        return view('profile.announcements.history', compact('announcements'));
    }

    /**
     * Menampilkan riwayat barang temuan (items) milik pengguna.
     */
    public function foundItemsHistory()
    {
         $user = Auth::user();
    // TAMBAHKAN ->with('verification') untuk mengambil data verifikasi
         $items = Item::with('verification')
                 ->where('user_id', $user->id)
                 ->latest()
                 ->paginate(10);

        return view('profile.found.history', compact('items'));
    }

    /**
     * Menampilkan riwayat klaim (claims) milik pengguna.
     */
    public function claimedItemsHistory()
    {
        $user = Auth::user();
        // Mengambil dari model Claim, dan memuat data 'item' terkait untuk efisiensi
        $claims = Claim::with('item')
                       ->where('user_id', $user->id)
                       ->latest()
                       ->paginate(10);
        
        // Anda perlu membuat view ini: resources/views/profile/history/claims.blade.php
        return view('profile.claims.history', compact('claims'));
    }
}