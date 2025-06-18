<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Menampilkan daftar semua pengumuman.
     */
    public function index()
    {
        // Ambil semua pengumuman yang statusnya 'lost' (belum ditemukan)
        $announcements = Announcement::with('user')
                                    ->where('status', 'lost')
                                    ->latest()
                                    ->paginate(10);
        
        return view('user.announcements.index', compact('announcements'));
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        return view('user.announcements.create'); // Anda perlu membuat view ini
    }

    /**
     * Menyimpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lost_time' => 'required|date',
            'estimated_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcement_images', 'public');
            $validated['image'] = $path;
        }

        Auth::user()->announcements()->create($validated);

        return redirect()->route('user.announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit pengumuman.
     */
    public function edit(Announcement $announcement)
    {
        // Otorisasi: pastikan hanya pemilik yang bisa mengakses halaman edit
        $this->authorize('update', $announcement);

        // Gunakan view yang sama dengan 'create' dan kirim data pengumuman
        return view('user.announcements.create', compact('announcement'));
    }

    /**
     * Menyimpan perubahan dari form edit.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Otorisasi: pastikan hanya pemilik yang bisa mengupdate
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lost_time' => 'required|date',
            'estimated_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $path = $request->file('image')->store('announcement_images', 'public');
            $validated['image'] = $path;
        }

        $announcement->update($validated);

        // Redirect ke halaman riwayat setelah update, karena lebih relevan
        return redirect()->route('user.profile.announcementshistory')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Menghapus pengumuman dari database.
     */
    public function destroy(Announcement $announcement)
    {
        // Otorisasi: pastikan hanya pemilik yang bisa menghapus
        $this->authorize('delete', $announcement);

        // Hapus gambar dari storage jika ada
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        // Redirect kembali ke halaman sebelumnya (misal: halaman riwayat) dengan pesan sukses
        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }
    
}