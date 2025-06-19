@extends('layouts.master')

@section('title', 'Verifikasi Pengajuan Klaim')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.admin.claims.index') }}" class="text-gray-600 hover:text-blue-700 transition" title="Kembali">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Verifikasi Pengajuan Klaim</h1>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Detail Barang --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="w-full h-auto object-cover rounded-lg shadow-md border">
                    @else
                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $item->item_name }}</h2>
                    <p class="text-gray-600 text-sm">{{ $item->description }}</p>
                </div>
            </div>
        </div>

        {{-- Daftar Pengklaim --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
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
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                                    Serahkan Barang
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Belum ada yang mengajukan klaim untuk barang ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection