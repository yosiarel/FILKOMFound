<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    /**
     * Menyimpan permintaan klaim baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item  <-- Menggunakan Route-Model Binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Item $item)
    {
        $user = Auth::user();

        // 1. Validasi: Pengguna tidak boleh mengklaim barangnya sendiri.
        if ($user->id === $item->user_id) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengklaim barang yang Anda laporkan sendiri.');
        }

        // 2. Validasi: Barang harus berstatus 'found' untuk bisa diklaim.
        if ($item->status !== 'found') {
            return redirect()->back()->with('error', 'Barang ini sudah tidak tersedia atau sedang dalam proses klaim.');
        }

        // 3. Validasi: Pengguna tidak boleh mengklaim barang yang sama dua kali.
        $existingClaim = Claim::where('user_id', $user->id)
                                ->where('item_id', $item->id)
                                ->exists();

        if ($existingClaim) {
            return redirect()->back()->with('error', 'Anda sudah pernah mengajukan klaim untuk barang ini.');
        }

        // 4. Buat data klaim baru.
        Claim::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending', // Status awal klaim adalah 'pending'.
        ]);

        // 5. INI BAGIAN PENTING: Update status barang menjadi 'claimed'.
        $item->update(['status' => 'claimed']);

        // 6. Redirect dengan pesan sukses.
        return redirect()->route('user.items.index')->with('success', 'Klaim Anda berhasil diajukan dan akan segera diverifikasi oleh admin.');
    }
}