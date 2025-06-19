@extends('layouts.master')

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
                <a href="{{ route('user.announcements.create') }}" class="w-full md:w-auto inline-block text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors">
                    Buat Pengumuman
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        {{-- Form Filter dan Sort --}}
        <form action="{{ route('user.announcements.index') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm border">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="md:col-span-2 lg:col-span-2">
                    <label for="search" class="sr-only">Cari</label>
                    <input type="text" name="search" id="search" placeholder="Cari nama barang atau lokasi..." value="{{ request('search') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label for="lost_date" class="sr-only">Tanggal Hilang</label>
                    <input type="date" name="lost_date" id="lost_date" value="{{ request('lost_date') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">Filter</button>
                    <a href="{{ route('user.announcements.index') }}" class="w-full text-center py-2 px-4 border border-gray-300 rounded-lg hover:bg-gray-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="space-y-6">
            @forelse ($announcements as $announcement)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row items-start gap-6">
                    <div class="w-full sm:w-40 md:w-48 flex-shrink-0">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->name }}" class="w-full h-auto object-cover rounded-lg aspect-square">
                        @else
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

                        <div class="flex items-center justify-between mt-3 pt-2 border-t">
                            <p class="text-xs text-gray-400">
                                Diumumkan oleh: {{ $announcement->user->name ?? 'Anonim' }}
                            </p>
                            
                            <div class="flex items-center gap-3">
                                {{-- Tombol Report, hanya muncul untuk mahasiswa dan bukan pemilik --}}
                                @if(Auth::check() && Auth::user()->role === 'mahasiswa' && Auth::id() !== $announcement->user_id)
                                <form action="{{ route('user.announcements.report', $announcement->id) }}" method="POST" onsubmit="return confirm('Laporkan pengumuman ini ke admin?');">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-red-600 text-xs" title="Laporkan Pengumuman">
                                        <i class="fas fa-flag"></i> Laporkan
                                    </button>
                                </form>
                                @endif
                                
                                {{-- Tombol Edit/Hapus, hanya muncul untuk admin atau pemilik --}}
                                @can('update', $announcement)
                                <a href="{{ route('user.announcements.edit', $announcement->id) }}" class="text-gray-500 hover:text-blue-600" title="Edit Pengumuman">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $announcement)
                                <form action="{{ route('user.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600" title="Hapus Pengumuman">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Belum ada pengumuman barang hilang atau tidak ada yang cocok dengan filter Anda.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>

    </div>
</div>
@endsection