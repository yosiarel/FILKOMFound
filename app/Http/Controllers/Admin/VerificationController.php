<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    // ... method index, approve, reject tetap sama ...

    /**
     * --- METHOD BARU DI SINI ---
     * Menampilkan satu halaman detail barang yang dinamis untuk admin.
     */
    public function showUnifiedDetail(Item $item)
    {
        // Muat data relasi yang diperlukan berdasarkan status
        // Jika sedang diklaim, muat semua data klaim beserta user-nya
        if ($item->status === 'claimed') {
            $item->load('claims.user');
        }
        // Jika sudah dikembalikan, muat data klaim yang berhasil (diterima)
        elseif ($item->status === 'returned') {
            $item->load(['claims' => function ($query) {
                $query->where('status', 'diterima')->with('user');
            }]);
        }
        
        // Kirim data item ke view baru yang akan kita buat
        return view('admin.items.show_detail', compact('item'));
    }

    public function index()
    {
        $unverifiedItems = Item::whereNull('verified_at')
                                ->latest()
                                ->paginate(12);

        return view('admin.verifications.index', compact('unverifiedItems'));
    }

    public function show(Item $item)
    {
        if ($item->verified_at) {
        }
        return view('verifications.show', compact('item'));
    }

    public function approve(Item $item)
    {
        if (!$item->verified_at) {
            $item->update(['verified_at' => now()]);
            // Redirect ke detail view yang baru, bukan ke index
            return redirect()->route('user.admin.items.show_detail', $item)->with('success', 'Barang berhasil diverifikasi dan sekarang tampil untuk publik.');
        }
        return redirect()->back()->with('error', 'Barang ini sudah pernah diverifikasi sebelumnya.');
    }

    public function reject(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        // Redirect ke daftar verifikasi, karena item sudah tidak ada
        return redirect()->route('user.admin.verifications.index')->with('success', 'Laporan temuan berhasil ditolak dan dihapus.');
    }
}