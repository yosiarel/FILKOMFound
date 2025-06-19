<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Announcement;
use App\Models\Item;
use App\Models\Claim;
use App\Models\User; // <-- Pastikan ini ada

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman utama profil pengguna.
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request)
    {
        // --- PERBAIKAN DI SINI ---
        // Ambil ID user yang login, lalu cari modelnya secara eksplisit.
        $userId = Auth::id();
        $user = User::find($userId);

        if (!$user) {
            // Penanganan jika user tidak ditemukan (kasus langka)
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Sekarang method .save() akan terdefinisi dengan benar
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan riwayat laporan kehilangan (announcements) milik pengguna.
     */
    public function announcementsHistory()
    {
        $user = Auth::user();
        $announcements = Announcement::where('user_id', $user->id)
                                    ->latest()
                                    ->paginate(10);

        return view('profile.announcements.history', compact('announcements'));
    }

    /**
     * Menampilkan riwayat barang temuan (items) milik pengguna.
     */
    public function foundItemsHistory()
    {
        $user = Auth::user();
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
        $claims = Claim::with('item')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->paginate(10);
        
        return view('profile.claims.history', compact('claims'));
    }
}