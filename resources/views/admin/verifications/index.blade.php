@extends('layouts.master')

@section('title', 'Verifikasi Laporan Temuan')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Verifikasi Laporan Temuan</h1>
        
        <p class="text-gray-600 mb-6 max-w-2xl">
            Halaman ini berisi daftar barang yang telah dilaporkan oleh mahasiswa dan menunggu verifikasi dari Anda. Klik pada barang untuk mencocokkan detailnya dengan fisik barang yang diserahkan.
        </p>

        @if ($unverifiedItems->isEmpty())
            <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                <p class="text-gray-500 font-semibold">Tidak ada laporan temuan yang perlu diverifikasi saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach ($unverifiedItems as $item)
                    <a href="{{ route('user.admin.verifications.show', $item->id) }}" class="block bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden group hover:shadow-lg hover:border-red-500 transition-all duration-300">
                        <div class="relative">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}"
                                    class="w-full h-40 object-cover object-center">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Diverifikasi
                                </span>
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-md text-gray-900 truncate group-hover:text-red-600 transition">{{ $item->item_name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Dilaporkan pada: {{ $item->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $unverifiedItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection