<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    // Method index, show, dan handover tetap sama seperti sebelumnya...

    public function index()
    {
        $items = Item::where('status', 'claimed')
                    ->whereNotNull('verified_at')
                    ->withCount('claims')
                    ->latest('updated_at')
                    ->paginate(10);
                    
        return view('admin.claims.index', compact('items'));
    }

    public function show(Item $item)
    {
        if ($item->status !== 'claimed') {
            return redirect()->route('user.admin.claims.index')->with('error', 'Barang ini tidak sedang dalam proses klaim.');
        }

        $item->load('claims.user');

        return view('admin.claims.show', compact('item'));
    }

    public function handover(Request $request, Claim $claim)
    {
        DB::beginTransaction();
        try {
            $item = $claim->item;

            if ($item->status !== 'claimed') {
                return redirect()->back()->with('error', 'Status barang sudah berubah. Aksi tidak dapat dilanjutkan.');
            }

            $item->update(['status' => 'returned']);
            $claim->update(['status' => 'diterima']);

            Claim::where('item_id', $item->id)
                 ->where('id', '!=', $claim->id)
                 ->update(['status' => 'ditolak']);
            
            DB::commit();

            return redirect()->route('user.admin.claims.index')->with('success', 'Barang berhasil diserahkan kepada ' . $claim->user->name . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data. ' . $e->getMessage());
        }
    }

    /**
     * --- METHOD BARU DI SINI ---
     * Menampilkan riwayat barang yang telah dikembalikan.
     */
    public function history()
    {
        // Ambil semua klaim yang statusnya 'diterima',
        // lalu sertakan data item dan user yang terkait.
        $successfulClaims = Claim::where('status', 'diterima')
                                ->with(['item.user', 'user']) // item.user = penemu, user = penerima
                                ->latest()
                                ->paginate(15);

        return view('admin.claims.history', compact('successfulClaims'));
    }
}