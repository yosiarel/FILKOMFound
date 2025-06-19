@extends('layouts.master')

@section('title', 'Verifikasi Laporan')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Halaman dengan Tombol Kembali --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ url()->previous(route('user.items.index')) }}" class="text-gray-600 hover:text-blue-700 transition" title="Kembali">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Verifikasi Laporan
            </h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p class="font-bold">Sukses</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Konten Utama --}}
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    
                    {{-- Kolom Kiri: Gambar Barang --}}
                    <div>
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="w-full h-auto object-cover rounded-lg shadow-md border">
                        @else
                            <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-5xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Detail Informasi --}}
                    <div class="flex flex-col">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $item->item_name }}</h2>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <p class="font-semibold text-gray-500">Tanggal Ditemukan</p>
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-500">Lokasi Temuan</p>
                                <p class="text-gray-800">{{ $item->location }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="font-semibold text-gray-500 text-sm">Deskripsi</p>
                            <p class="text-gray-800 text-sm whitespace-pre-line">{{ $item->description }}</p>
                        </div>
                        
                        <div class="mb-6">
                            <p class="font-semibold text-gray-500 text-sm">Status</p>
                            @php
                                $statusText = $item->admin_formatted_status;
                                $bgColor = 'bg-gray-100 text-gray-800';
                                if ($statusText == 'Belum Diverifikasi') { $bgColor = 'bg-red-100 text-red-800'; }
                                elseif ($statusText == 'Sedang Diajukan') { $bgColor = 'bg-yellow-100 text-yellow-800'; }
                                elseif ($statusText == 'Belum Dikembalikan') { $bgColor = 'bg-blue-100 text-blue-800'; }
                                elseif ($statusText == 'Sudah Dikembalikan') { $bgColor = 'bg-green-100 text-green-800'; }
                            @endphp
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $bgColor }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div class="mt-auto pt-4 border-t">
                             <p class="font-semibold text-gray-500 text-sm">Dilaporkan oleh:</p>
                             <div class="flex items-center gap-3 mt-2">
                                @if($item->user)
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center text-red-500 font-bold">!</div>
                                    <div>
                                        <p class="font-semibold text-red-700">Pengguna Dihapus</p>
                                    </div>
                                @endif
                             </div>
                        </div>
                    </div>
                </div>

                {{-- Hanya tampilkan tombol aksi JIKA item belum diverifikasi --}}
                @if(!$item->verified_at)
                <div class="bg-gray-50 px-6 py-4 border-t flex items-center justify-end gap-4">
                    <form action="{{ route('user.admin.verifications.reject', ['item' => $item->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak laporan ini? Laporan akan dihapus permanen.');">
                        @csrf
                        <button type="submit" class="font-semibold text-red-600 hover:text-red-800 transition">Tolak</button>
                    </form>
                    <form action="{{ route('user.admin.verifications.approve', ['item' => $item->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                            Verifikasi Laporan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection