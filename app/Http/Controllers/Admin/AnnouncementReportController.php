<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementReportController extends Controller
{
    /**
     * Menampilkan daftar semua aduan pengumuman.
     */
    public function index()
    {
        $reports = AnnouncementReport::with(['announcement.user', 'user'])
                                    ->where('status', 'pending')
                                    ->latest()
                                    ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Menghapus pengumuman yang dilaporkan.
     * Ini adalah tindakan destruktif.
     */
    public function destroyAnnouncement(Announcement $announcement)
    {
        // Otorisasi, pastikan admin bisa menghapus
        $this->authorize('delete', $announcement);

        // Hapus semua laporan terkait pengumuman ini terlebih dahulu
        AnnouncementReport::where('announcement_id', $announcement->id)->delete();

        // Hapus gambar dari storage jika ada
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        // Hapus pengumuman
        $announcement->delete();
        
        return redirect()->route('user.admin.reports.announcements.index')->with('success', 'Pengumuman yang dilaporkan telah berhasil dihapus.');
    }

    /**
     * Mengabaikan laporan (tandai sebagai sudah ditinjau).
     * Ini hanya menyembunyikan laporan dari daftar, tidak menghapus pengumumannya.
     */
    public function resolveReport(AnnouncementReport $report)
    {
        $report->update(['status' => 'reviewed']);
        return redirect()->route('user.admin.reports.announcements.index')->with('success', 'Laporan telah ditandai sebagai selesai.');
    }
}