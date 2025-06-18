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
     * Menampilkan daftar barang yang ditemukan oleh pengguna.
     */
    public function index()
    {
        $items = Item::where('user_id', Auth::id())->latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('item_images', 'public');
        }

        $validated['user_id'] = Auth::id(); // Menambahkan ID pengguna yang sedang login

        Item::create($validated);

        return redirect()->route('user.items.index')->with('success', 'Barang temuan berhasil dilaporkan.');
    }

    /**
     * Menampilkan form untuk mengedit data barang temuan.
     */
    public function edit(Item $item)
    {
        // Otorisasi menggunakan ItemPolicy: Apakah pengguna ini berhak meng-update item ini?
        $this->authorize('update', $item);

        return view('user.items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang temuan di database.
     */
    public function update(Request $request, Item $item)
    {
        // Otorisasi menggunakan ItemPolicy: Apakah pengguna ini berhak meng-update item ini?
        $this->authorize('update', $item);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'found_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada sebelum mengunggah yang baru
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('item_images', 'public');
        }

        $item->update($validated);

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang temuan dari database.
     */
    public function destroy(Item $item)
    {
        // Otorisasi menggunakan ItemPolicy: Apakah pengguna ini berhak menghapus item ini?
        $this->authorize('delete', $item);

        // Hapus gambar terkait dari storage
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil dihapus.');
    }
}