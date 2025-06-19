@extends('layouts.master')

@section('title', 'Riwayat Klaim Barang')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.profile.index') }}" class="text-gray-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Riwayat Klaim Barang
            </h1>
        </div>

        <div class="space-y-4">
            @forelse ($claims as $claim)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-grow">
                            <h2 class="font-bold text-xl text-gray-900">{{ $claim->item->item_name }}</h2>
                            <p class="text-sm text-gray-500">Klaim diajukan: {{ $claim->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @php
                                $statusClass = '';
                                if ($claim->status == 'diterima') $statusClass = 'bg-green-100 text-green-800';
                                elseif ($claim->status == 'ditolak') $statusClass = 'bg-red-100 text-red-800';
                                else $statusClass = 'bg-yellow-100 text-yellow-800';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                Status: {{ ucfirst($claim->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-hand-paper text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Anda belum pernah mengajukan klaim barang.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $claims->links() }}
        </div>
    </div>
</div>
@endsection