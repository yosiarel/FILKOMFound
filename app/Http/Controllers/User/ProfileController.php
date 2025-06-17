<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Item; // <-- Ganti dari LostAndFoundItem ke Item
use App\Models\Claim; // Pastikan model Claim juga ada jika digunakan

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // Anda bisa menambahkan data lain yang dibutuhkan di sini, misalnya:
        // $user->load('lostItems', 'foundItems', 'claims'); // Jika Anda memiliki relasi di model User
        return view('profile.index', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            // 'nim' => 'nullable|string|max:20|unique:users,nim,' . $user->id, // jika ada di tabel users
            // 'profile_picture' => 'nullable|image|max:2048', // jika ada upload gambar
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan riwayat laporan kehilangan pengguna (lost items).
     * Kolom 'status' di model Item Anda perlu mengindikasikan apakah itu 'lost' atau 'found'.
     *
     * @return \Illuminate\View\View
     */
    public function announcements()
    {
        $user = Auth::user();

        // Mengambil semua item yang dilaporkan hilang oleh pengguna ini
        // Asumsi kolom 'status' di tabel 'items' digunakan untuk membedakan 'lost'/'found'
        $lostItems = Item::where('user_id', $user->id)
                            ->where('status', 'lost') // <-- Menggunakan kolom 'status'
                            ->orderByDesc('created_at')
                            ->get();

        return view('profile.announcement', compact('lostItems'));
    }

    /**
     * Menampilkan riwayat barang temuan pengguna (found items).
     *
     * @return \Illuminate\View\View
     */
    public function foundItems()
    {
        $user = Auth::user();

        // Mengambil semua item yang ditemukan oleh pengguna ini
        // Asumsi kolom 'status' di tabel 'items' digunakan untuk membedakan 'lost'/'found'
        $foundItems = Item::where('user_id', $user->id)
                            ->where('status', 'found') // <-- Menggunakan kolom 'status'
                            ->orderByDesc('created_at')
                            ->get();

        return view('profile.found', compact('foundItems'));
    }

    /**
     * Menampilkan riwayat klaim pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function claimedItems()
    {
        $user = Auth::user();

        // Mengambil semua klaim yang dibuat oleh pengguna ini
        // Asumsi ada model Claim dan kolom 'user_id' di tabel 'claims'
        $claims = Claim::where('user_id', $user->id)
                        ->orderByDesc('created_at')
                        ->get();

        return view('profile.claim', compact('claims'));
    }
}