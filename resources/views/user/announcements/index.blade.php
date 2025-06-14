@extends('layouts.app')

@section('title', 'Pengumuman Barang Hilang')

@section('content')

{{-- Background utama halaman --}}
<div class="bg-orange-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Pengumuman Barang Hilang
            </h1>
            <div>
                {{-- DIUBAH: href sekarang mengarah ke route untuk membuat pengumuman --}}
                <a href="{{ route('user.announcements.create') }}" class="w-full md:w-auto inline-block text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors">
                    Buat Pengumuman
                </a>
            </div>
        </div>

        {{-- TAMBAHAN: Area untuk menampilkan notifikasi sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="space-y-6">
            {{-- DIHAPUS: Blok @php dengan data dummy sudah dihapus dari sini --}}
            @forelse ($announcements as $announcement)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row items-start gap-6">
                    <div class="w-full sm:w-40 md:w-48 flex-shrink-0">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->name }}" class="w-full h-auto object-cover rounded-lg aspect-square">
                        @else
                            {{-- Placeholder jika tidak ada gambar --}}
                            <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center aspect-square">
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow">
                        <h3 class="font-bold text-xl text-gray-900 mb-3">{{ $announcement->name }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-4 text-sm">
                            <div>
                                <p class="font-semibold text-gray-500">Tanggal Perkiraan Hilang:</p>
                                {{-- Menggunakan Carbon untuk memformat tanggal dari database --}}
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($announcement->lost_time)->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                             <div>
                                <p class="font-semibold text-gray-500">Lokasi Perkiraan:</p>
                                <p class="text-gray-800">{{ $announcement->estimated_location }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="font-semibold text-gray-500 text-sm">Deskripsi Barang:</p>
                            <p class="text-gray-800 text-sm">{{ $announcement->description }}</p>
                        </div>

                        <p class="text-xs text-gray-400 mt-3 pt-2 border-t">
                            Diumumkan oleh: {{ $announcement->user->name ?? 'Anonim' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Belum ada pengumuman barang hilang.</p>
                </div>
            @endforelse
        </div>
        
        {{-- DIUBAH: Link pagination diaktifkan --}}
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>

    </div>
</div>
@endsection