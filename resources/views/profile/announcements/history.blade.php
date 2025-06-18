@extends('layouts.master')

@section('title', 'Riwayat Laporan Kehilangan')

@section('content')
{{-- Sertakan script Alpine.js untuk fungsionalitas accordion --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Halaman --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.profile.index') }}" class="text-gray-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Riwayat Laporan Kehilangan
            </h1>
        </div>

        {{-- Tombol Sort (statis sesuai desain) --}}
        <div class="mb-6">
            <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-sort"></i>
                Sort
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
        </div>

        {{-- Daftar Laporan --}}
        <div class="space-y-4">
            @forelse ($announcements as $announcement)
                {{-- Card untuk setiap item, menggunakan Alpine.js untuk state open/close --}}
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    {{-- Bagian Header Card (yang selalu terlihat) --}}
                    <div class="p-4 flex items-center justify-between gap-4 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                        <div class="flex-grow">
                            <h2 class="font-bold text-xl text-gray-900">{{ $announcement->name }}</h2>
                            <p class="text-sm text-gray-500">Tanggal Laporan: {{ $announcement->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="flex items-center gap-4 flex-shrink-0">
                            {{-- CATATAN: Logika status ini memerlukan penambahan kolom 'status' di tabel announcements --}}
                            @if($announcement->status === 'found')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Sudah Ditemukan</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Belum Ditemukan</span>
                            @endif

                            {{-- Form untuk tombol hapus --}}
                            <form action="{{ route('user.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 p-2" title="Hapus Laporan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Bagian Detail Card (yang bisa expand/collapse) --}}
                    <div x-show="open" x-transition class="px-4 pb-4 border-t border-gray-200">
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Kolom Gambar --}}
                            @if($announcement->image)
                            <div class="md:col-span-1">
                                <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->name }}" class="w-full h-auto object-cover rounded-lg">
                            </div>
                            @endif

                            {{-- Kolom Detail Teks --}}
                            <div class="space-y-4 {{ $announcement->image ? 'md:col-span-2' : 'md:col-span-3' }}">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="font-semibold text-gray-500">Perkiraan Waktu Terakhir</p>
                                        <p class="text-gray-800">{{ \Carbon\Carbon::parse($announcement->lost_time)->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-500">Perkiraan Lokasi Terakhir</p>
                                        <p class="text-gray-800">{{ $announcement->estimated_location }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-500 text-sm">Deskripsi Barang</p>
                                    <p class="text-gray-800 text-sm whitespace-pre-line">{{ $announcement->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Anda belum pernah membuat laporan kehilangan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection