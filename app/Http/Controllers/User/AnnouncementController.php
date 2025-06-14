<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class AnnouncementController extends Controller
{
    /**
     * Menampilkan daftar semua pengumuman.
     */
    public function index()
    {
        // Mengambil data dari database, diurutkan dari yang terbaru
        $announcements = Announcement::with('user')->latest()->paginate(10);
        
        // Mengirim data $announcements ke view
        return view('user.announcements.index', compact('announcements'));
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        // Langsung tampilkan view form-nya
        return view('user.announcements.create');
    }

    /**
     * Menyimpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lost_time' => 'required|date',
            'estimated_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        // 2. Handle jika ada file gambar yang di-upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcement_images', 'public');
            $validated['image'] = $path;
        }

        // 3. Simpan data ke database dan kaitkan dengan user yang login
        Auth::user()->announcements()->create($validated);

        // 4. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('user.announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logika untuk menampilkan detail satu pengumuman bisa ditambahkan di sini
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        // Nanti ini akan digunakan untuk fitur edit
        return view('user.announcements.create', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logika untuk menyimpan perubahan dari form edit bisa ditambahkan di sini
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logika untuk menghapus pengumuman bisa ditambahkan di sini
    }
}