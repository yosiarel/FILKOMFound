<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementReport; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * Menampilkan daftar semua pengumuman dengan filter dan sort.
     */
    public function index(Request $request)
    {
        $announcementsQuery = Announcement::with('user')
                                    ->where('status', 'lost');
        
        $announcementsQuery->filter($request->only(['search', 'lost_date']));

        if ($request->get('sort') === 'asc') {
            $announcementsQuery->orderBy('lost_time', 'asc');
        } else {
            $announcementsQuery->orderBy('lost_time', 'desc');
        }

        $announcements = $announcementsQuery->paginate(10)->withQueryString();
        
        return view('user.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('user.announcements.create');
    }

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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->announcements()->create($validated);

        return redirect()->route('user.announcements.index')->with('success', 'Pengumuman telah dibuat. Silahkan cek Daftar Barang Temuan secara berkala.');
    }
    
    /**
     * Fungsi baru untuk menangani report dari user.
     * INI ADALAH FUNGSI YANG HILANG DI FILE ANDA.
     */
    public function report(Request $request, Announcement $announcement)
    {
        $user = Auth::user();

        if ($user->id === $announcement->user_id) {
            return back()->with('error', 'Anda tidak dapat melaporkan pengumuman Anda sendiri.');
        }

        $existingReport = AnnouncementReport::where('announcement_id', $announcement->id)
                                            ->where('user_id', $user->id)
                                            ->exists();

        if ($existingReport) {
            return back()->with('error', 'Anda sudah pernah melaporkan pengumuman ini.');
        }

        AnnouncementReport::create([
            'announcement_id' => $announcement->id,
            'user_id' => $user->id,
            'reason' => 'Dilaporkan oleh pengguna.',
        ]);
        
        return back()->with('success', 'Aduan Anda telah terkirim dan akan ditinjau oleh admin.');
    }

    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        return view('user.announcements.create', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lost_time' => 'required|date',
            'estimated_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $path = $request->file('image')->store('announcement_images', 'public');
            $validated['image'] = $path;
        }

        $announcement->update($validated);

        // --- PERBAIKAN REDIRECT DI SINI ---
        // Arahkan kembali ke halaman daftar pengumuman utama
        return redirect()->route('user.announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();
        
        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}