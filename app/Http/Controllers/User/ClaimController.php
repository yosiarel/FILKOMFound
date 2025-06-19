<?php

namespace App\Http\Controllers\User;

use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    /**
     * Menyimpan data klaim baru dari pengguna.
     */
    public function store(Item $item)
    {
        // 1. Validasi Awal: Pengguna tidak bisa mengklaim barangnya sendiri.
        if (Auth::id() === $item->user_id) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengklaim barang yang Anda laporkan sendiri.');
        }

        // 2. Validasi Duplikat: Pengguna tidak bisa mengklaim barang yang sama dua kali.
        $existingClaim = Claim::where('item_id', $item->id)
                            ->where('user_id', Auth::id())
                            ->exists(); // Cukup gunakan exists() untuk efisiensi.

        if ($existingClaim) {
            return redirect()->back()->with('error', 'Anda sudah pernah mengklaim barang ini.');
        }

        // 3. Validasi Status: Barang tidak bisa diklaim jika statusnya sudah dikembalikan.
        if ($item->status === 'returned') {
            return redirect()->back()->with('error', 'Barang ini sudah dikembalikan dan tidak bisa diklaim lagi.');
        }

        // 4. Lakukan Aksi: Buat data klaim baru untuk pengguna ini.
        Claim::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'status' => 'pending' // Status awal untuk setiap klaim baru.
        ]);

        // 5. Update status barang menjadi 'claimed'.
        // Ini aman untuk dijalankan berkali-kali karena tidak akan mengubah apa pun jika statusnya sudah 'claimed'.
        $item->status = 'claimed';
        $item->save();

        // 6. Berikan pesan sukses.
        return redirect()->back()->with('success', 'Klaim Anda berhasil diajukan dan akan segera diverifikasi oleh admin.');
    }
}