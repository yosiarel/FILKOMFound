@extends('layouts.master')

@section('title', 'Detail Barang Temuan')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.items.index') }}" class="text-gray-600 hover:text-blue-700 transition" title="Kembali ke Daftar Barang">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Barang Temuan</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- === BAGIAN DETAIL UTAMA (TAMPIL UNTUK SEMUA STATUS) === --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="w-full h-auto object-cover rounded-lg shadow-md border">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    @endif
                </div>
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">{{ $item->item_name }}</h2>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $item->admin_formatted_status == 'Belum Diverifikasi' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }} mt-2 inline-block">
                            {{ $item->admin_formatted_status }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500">Ditemukan pada:</p>
                        <p class="text-gray-800">{{ $item->found_date->translatedFormat('d F Y') }} di {{ $item->location }}</p>
                    </div>
                     <div>
                        <p class="font-semibold text-gray-500">Deskripsi:</p>
                        <p class="text-gray-800 whitespace-pre-line">{{ $item->description }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500">Dilaporkan oleh:</p>
                        <p class="text-gray-800">{{ $item->user->name }} ({{ $item->user->nim }})</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- === BAGIAN AKSI DINAMIS (BERUBAH SESUAI STATUS) === --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">

            {{-- 1. JIKA STATUS "BELUM DIVERIFIKASI" --}}
            @if(!$item->verified_at)
                <div class="p-6 text-center space-x-4">
                    <form action="{{ route('user.admin.verifications.reject', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin MENOLAK laporan ini? Laporan akan dihapus.');">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded-lg transition">Tolak</button>
                    </form>
                    <form action="{{ route('user.admin.verifications.approve', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin MEMVERIFIKASI laporan ini?');">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-lg transition">Verifikasi Laporan</button>
                    </form>
                </div>

            {{-- 2. JIKA STATUS "SEDANG DIAJUKAN" --}}
            @elseif($item->status === 'claimed')
                <div class="p-4 border-b">
                    <h3 class="font-bold text-lg">Daftar Mahasiswa yang Mengklaim</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse ($item->claims as $claim)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $claim->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $claim->user->nim }} - {{ $claim->user->email }}</p>
                            </div>
                            <div>
                                <form action="{{ route('user.admin.claims.handover', $claim->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyerahkan barang ini kepada {{ $claim->user->name }}? Aksi ini tidak dapat dibatalkan.');">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                                        Kembalikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Data klaim tidak ditemukan.
                        </div>
                    @endforelse
                </div>
            
            {{-- 3. JIKA STATUS "SUDAH DIKEMBALIKAN" --}}
            @elseif($item->status === 'returned')
                <div class="p-6 bg-green-50 rounded-b-xl">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                        <div>
                            <h3 class="font-bold text-lg text-green-800">Barang Telah Diserahkan</h3>
                            @if($item->claims->isNotEmpty())
                                <p class="text-green-700">Diserahkan kepada <strong>{{ $item->claims->first()->user->name }}</strong> pada {{ $item->claims->first()->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}.</p>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- 4. JIKA STATUS "BELUM DIKEMBALIKAN" (SUDAH VERIFIKASI TAPI BELUM ADA YG KLAIM) --}}
            @elseif($item->status === 'found')
                <div class="p-6 bg-blue-50 rounded-b-xl text-center">
                    <p class="font-semibold text-blue-800">Barang ini sudah terverifikasi dan menunggu ada yang mengklaim.</p>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection