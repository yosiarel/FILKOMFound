@extends('layouts.master')

@section('title', 'Riwayat Laporan Temuan')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('user.profile.index') }}" class="text-gray-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Riwayat Laporan Temuan Anda
            </h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($items as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-grow">
                            <h2 class="font-bold text-xl text-gray-900">{{ $item->item_name }}</h2>
                            <p class="text-sm text-gray-500">Dilaporkan: {{ $item->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            {{-- --- PERBAIKAN LOGIKA STATUS DI SINI --- --}}
                            @php
                                $statusText = $item->admin_formatted_status;
                                $bgColor = 'bg-gray-100 text-gray-800'; // Default
                                if ($statusText == 'Belum Diverifikasi') { $bgColor = 'bg-red-100 text-red-800'; }
                                elseif ($statusText == 'Sedang Diajukan') { $bgColor = 'bg-yellow-100 text-yellow-800'; }
                                elseif ($statusText == 'Belum Dikembalikan') { $bgColor = 'bg-blue-100 text-blue-800'; }
                                elseif ($statusText == 'Sudah Dikembalikan') { $bgColor = 'bg-green-100 text-green-800'; }
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $bgColor }}">
                                Status: {{ $statusText }}
                            </span>
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

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection