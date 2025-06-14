<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- DIUBAH: Kita import Auth Facade

class ItemController extends Controller
{
    /**
     * Menampilkan daftar semua item milik pengguna.
     */
    public function index()
    {
        $items = Item::latest()->paginate(10);
        return view('user.items.index', compact('items'));
    }

    /**
     * Menampilkan form untuk membuat item baru.
     */
    public function create()
    {
        return view('user.items.create');
    }

    /**
     * Menyimpan item baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'found_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Handle jika ada file gambar yang di-upload
        if ($request->hasFile('image')) {
            // DIUBAH: Sintaks upload file disederhanakan dan diperbaiki
            $path = $request->file('image')->store('item_images', 'public');
            $validated['image'] = $path;
        }

        // 3. Simpan data ke database
        // DIUBAH: Menggunakan Auth::user() agar lebih eksplisit untuk editor kode
        Auth::user()->items()->create($validated);

        // 4. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('user.items.index')->with('success', 'Barang berhasil dilaporkan!');
    }

    /**
     * Menampilkan detail satu item.
     */
    public function show(Item $item)
    {
        return view('user.items.show', compact('item'));
    }

    /**
     * Menampilkan form untuk mengedit item yang sudah ada.
     */
    public function edit(Item $item)
    {
        // abort_if($item->user_id !== Auth::id(), 403, 'Anda tidak diizinkan mengedit barang ini.');
        return view('user.items.create', compact('item'));
    }

    /**
     * Mengupdate data item yang ada di database.
     */
    public function update(Request $request, Item $item)
    {
        // abort_if($item->user_id !== Auth::id(), 403, 'Anda tidak diizinkan mengedit barang ini.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'found_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $path = $request->file('image')->store('item_images', 'public');
            $validated['image'] = $path;
        }

        $item->update($validated);
        return redirect()->route('user.items.index')->with('success', 'Data barang berhasil diupdate!');
    }

    /**
     * Menghapus item dari database.
     */
    public function destroy(Item $item)
    {
        // abort_if($item->user_id !== Auth::id(), 403, 'Anda tidak diizinkan menghapus barang ini.');

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('user.items.index')->with('success', 'Data barang berhasil dihapus!');
    }
}