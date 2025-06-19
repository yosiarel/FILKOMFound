@extends('layouts.master')

@section('title', 'Daftar Aduan Pengumuman')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Daftar Aduan Pengumuman</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            @forelse ($reports as $report)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-red-200">
                    {{-- Detail Pengumuman yang Dilaporkan --}}
                    <div class="border-b pb-4 mb-4">
                        <h3 class="font-bold text-lg text-gray-900">{{ $report->announcement->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $report->announcement->description }}</p>
                        <p class="text-xs text-gray-400 mt-2">Diumumkan oleh: {{ $report->announcement->user->name }}</p>
                    </div>

                    {{-- Info Pelapor dan Aksi Admin --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Dilaporkan oleh:</p>
                            <p class="text-sm text-gray-600">{{ $report->user->name }} ({{ $report->user->nim }})</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('user.admin.reports.resolve', $report->id) }}" method="POST" onsubmit="return confirm('Abaikan laporan ini? Laporan akan dianggap selesai ditinjau.');">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-semibold py-2 px-4 rounded-lg transition">
                                    Abaikan
                                </button>
                            </form>
                            <form action="{{ route('user.admin.reports.announcements.destroy', $report->announcement->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENGHAPUS pengumuman ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                                    Hapus Pengumuman
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Tidak ada aduan pengumuman yang perlu ditinjau.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection