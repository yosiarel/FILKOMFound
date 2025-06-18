@extends('layouts.master')

@section('title', 'Riwayat Barang Temuan')

@section('content')
{{-- Sertakan script Alpine.js untuk fungsionalitas accordion --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    .riwayat-image {
        width: 100%; /* Membuat gambar responsif terhadap container */
        max-width: 200px; /* Batas lebar maksimum gambar */
        height: auto;
        max-height: 150px; /* Batas tinggi maksimum gambar */
    }
</style>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Halaman --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.profile.index') }}" class="text-gray-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Riwayat Temuan
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

        {{-- Daftar Barang Temuan --}}
        <div class="space-y-4">
            @forelse ($items as $item)
                {{-- Card untuk setiap item --}}
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    {{-- Bagian Header Card (yang selalu terlihat) --}}
                    <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                        <div class="flex-grow">
                            <h2 class="font-bold text-lg text-gray-900">{{ $item->item_name }}</h2>
                            <p class="text-sm text-gray-500">
                                <span>{{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d F Y') }}</span>
                                <span class="mx-1">&middot;</span>
                                <span>{{ $item->location }}</span>
                            </p>
                        </div>
                        <div class="w-full sm:w-auto flex-shrink-0">
                            {{-- Logika untuk menampilkan status berdasarkan verifikasi --}}
                            @if(!$item->verification)
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Belum Diverifikasi</span>
                            @elseif($item->verification->status == 'rejected')
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Verifikasi Ditolak</span>
                            @else
                                {{-- Jika sudah diverifikasi, tampilkan status utama item --}}
                                <span class="px-3 py-1 text-xs font-semibold 
                                    @if($item->status == 'returned') bg-green-100 text-green-800 
                                    @else bg-blue-100 text-blue-800 @endif rounded-full">
                                    {{ $item->formatted_status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Bagian Detail Card (yang bisa expand/collapse) --}}
                    <div x-show="open" x-transition class="px-4 pb-4 border-t border-gray-200">
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($item->image)
                            <div class="md:col-span-1">
                                {{-- Kita buat 'frame' dengan tinggi dan lebar maksimum agar rapi --}}
                                <div class="w-full max-w-[200px] h-[150px] bg-gray-200 flex items-center justify-center rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" 
                                        class="object-contain h-full w-full">
                                </div>
                            </div>
                            @endif


                            <div class="space-y-4 {{ $item->image ? 'md:col-span-2' : 'md:col-span-3' }}">
                                <div>
                                    <p class="font-semibold text-gray-500 text-sm">Deskripsi Barang:</p>
                                    <p class="text-gray-800 text-sm whitespace-pre-line">{{ $item->description }}</p>
                                </div>
                                @if($item->verification)
                                <div>
                                    <p class="font-semibold text-gray-500 text-sm">Diverifikasi oleh Admin:</p>
                                    <p class="text-gray-800 text-sm">
                                        {{ $item->verification->created_at->translatedFormat('d F Y - H:i') }} WIB
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Anda belum pernah melaporkan barang temuan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection