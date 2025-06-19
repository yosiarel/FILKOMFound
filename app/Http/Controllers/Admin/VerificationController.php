<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Menampilkan halaman detail untuk verifikasi satu item.
     */
    public function show(Item $item)
    {
        // Cukup muat relasi user (pelapor)
        $item->load('user'); 
        
        return view('verifications.show', compact('item'));
    }

    /**
     * Menyetujui laporan barang temuan (verifikasi awal).
     */
    public function approve(Item $item)
    {
        // Mencegah aksi ganda
        if ($item->verified_at) {
            return redirect()->back()->with('error', 'Laporan ini sudah pernah diverifikasi.');
        }

        // Update kolom verified_at dengan waktu saat ini
        $item->update(['verified_at' => now()]);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diverifikasi dan sekarang tampil di daftar publik.');
    }

    /**
     * Menolak dan menghapus laporan barang temuan.
     */
    public function reject(Item $item)
    {
        // Mencegah aksi ganda jika admin membuka halaman dari history
        if ($item->verified_at) {
            return redirect()->back()->with('error', 'Tidak bisa menolak laporan yang sudah diverifikasi.');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('user.items.index')->with('success', 'Laporan barang telah ditolak dan dihapus.');
    }
}