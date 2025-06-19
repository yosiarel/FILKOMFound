<?php

namespace App\Http\Controllers\User;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query()->with('user');

        if (Auth::check() && Auth::user()->role === 'admin') {
            // Admin bisa melihat semua item, tidak ada filter awal.
        } else {
            // Mahasiswa & tamu HANYA melihat item yang sudah diverifikasi.
            $query->whereNotNull('verified_at');
        }

        // --- PERBAIKAN DI SINI ---
        // Tambahkan withCount untuk menghitung jumlah klaim dari user yang sedang login
        // untuk setiap item. Hasilnya akan ada di properti 'claims_count'.
        if (Auth::check()) {
            $query->withCount(['claims' => function ($query) {
                $query->where('user_id', Auth::id());
            }]);
        }

        $query->filter($request->only(['search', 'status', 'found_date']));

        if ($request->get('sort') === 'asc') {
            $query->orderBy('found_date', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('found_date', 'desc')->orderBy('id', 'desc');
        }

        $items = $query->paginate(12)->withQueryString();

        return view('user.items.index', compact('items'));
    }
    public function create()
    {
        return view('user.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'found_date' => 'required|date|before_or_equal:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('item_images', 'public');
        }
        
        $user = User::find(Auth::id());
        $user->items()->create($validated);

        return redirect()->route('user.items.index')->with('success', 'Barang temuan berhasil dilaporkan dan akan segera diverifikasi oleh admin.');
    }

    public function show(Item $item)
    {
        return view('user.items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        return view('user.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
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

        return redirect()->route('user.items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}