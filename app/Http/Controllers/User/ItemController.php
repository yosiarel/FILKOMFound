<?php

namespace App\Http\Controllers\User;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar semua barang temuan yang bisa dilihat publik,
     * dengan fungsionalitas filter, sort, dan search.
     */
    public function index(Request $request)
    {
    // Memulai query untuk model Item
    $itemsQuery = Item::query();

    // --- UBAH BARIS INI ---
    // Tambahkan 'found_date' ke dalam array agar ikut difilter
    $itemsQuery->filter($request->only(['search', 'status', 'found_date']));

    // Terapkan sorting
    if ($request->get('sort') === 'asc') { // Menggunakan 'asc' untuk terlama
        $itemsQuery->orderBy('found_date', 'asc');
    } else {
        // Default sort adalah yang terbaru
        $itemsQuery->orderBy('found_date', 'desc');
    }

    // Eksekusi query dengan pagination dan sertakan query string (filter) di link pagination
    $items = $itemsQuery->paginate(12)->withQueryString();

    return view('user.items.index', compact('items'));
    }

    /**
     * Menampilkan form untuk membuat laporan barang temuan baru.
     */
    public function create()
    {
        return view('user.items.create');
    }

    /**
     * Menyimpan laporan barang temuan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('item_images', 'public');
        }

        $validated['user_id'] = Auth::id();

        Item::create($validated);

        // Redirect ke halaman daftar barang, bukan riwayat pribadi
        return redirect()->route('user.items.index')->with('success', 'Barang temuan berhasil dilaporkan dan akan segera diverifikasi oleh admin.');
    }

    /**
     * Menampilkan detail satu barang.
     * (Anda bisa membuat view detail jika diperlukan)
     */
    public function show(Item $item)
    {
        // Otorisasi: asumsikan semua orang bisa melihat detail
        return view('user.items.show', compact('item'));
    }


    /**
     * Menampilkan form untuk mengedit data barang temuan.
     */
    public function edit(Item $item)
    {
        // Otorisasi menggunakan ItemPolicy
        $this->authorize('update', $item);

        return view('user.items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang temuan di database.
     */
    public function update(Request $request, Item $item)
    {
        // Otorisasi menggunakan ItemPolicy
        $this->authorize('update', $item);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('item_images', 'public');
        }

        $item->update($validated);

        // Redirect ke halaman daftar barang
        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang temuan dari database.
     */
    public function destroy(Item $item)
    {
        // Otorisasi menggunakan ItemPolicy
        $this->authorize('delete', $item);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}