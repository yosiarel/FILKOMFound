@extends('layouts.app')

@section('title', 'Daftar Barang Temuan')

@section('content')

{{-- Bagian ini hanya untuk demo, nantinya data ini akan Anda dapatkan dari Controller --}}
@php
    $items = [
        (object) ['name' => 'Earphone', 'found_date' => \Carbon\Carbon::parse('2025-04-18'), 'location' => 'Gedung F', 'status' => 'Belum Dikembalikan'],
        (object) ['name' => 'Earphone', 'found_date' => \Carbon\Carbon::parse('2025-04-18'), 'location' => 'Gedung F', 'status' => 'Belum Dikembalikan'],
        (object) ['name' => 'Earphone', 'found_date' => \Carbon\Carbon::parse('2025-04-18'), 'location' => 'Gedung F', 'status' => 'Belum Dikembalikan'],
        (object) ['name' => 'Tumbler', 'found_date' => \Carbon\Carbon::parse('2025-04-17'), 'location' => 'Gedung H', 'status' => 'Sudah Dikembalikan'],
        (object) ['name' => 'Kunci Motor', 'found_date' => \Carbon\Carbon::parse('2025-04-16'), 'location' => 'Gazebo', 'status' => 'Belum Dikembalikan'],
    ];
@endphp

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
            Daftar Barang Temuan
        </h1>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <button class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-100 transition">
                    <i class="fas fa-sort"></i>
                    <span>Sort</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="search" name="search" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition">
                        Posted at <i class="fas fa-times ml-1"></i>
                    </button>
                    <button class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition">
                        Status <i class="fas fa-times ml-1"></i>
                    </button>
                </div>
            </div>

            <div>
                {{-- Ganti route() dengan route yang sesuai untuk form lapor barang --}}
                <a href="#" class="w-full sm:w-auto flex items-center justify-center bg-[#005EFF] hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition-colors">
                    Laporkan Temuan
                </a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse ($items as $item)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-grow">
                        <p class="font-bold text-lg text-gray-900">{{ $item->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $item->found_date->format('d F Y') }}
                            <span class="mx-1">&middot;</span>
                            {{ $item->location }}
                        </p>
                    </div>

                    <div class="w-full sm:w-auto flex-shrink-0 text-left sm:text-right">
                        @if($item->status == 'Belum Dikembalikan')
                            <span class="px-4 py-1.5 text-sm font-semibold text-orange-800 bg-orange-100 rounded-full">
                                {{ $item->status }}
                            </span>
                        @else
                            <span class="px-4 py-1.5 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                                {{ $item->status }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada barang yang ditemukan --}}
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Belum ada barang temuan yang dilaporkan.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection