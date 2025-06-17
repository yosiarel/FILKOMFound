@extends('layouts.master')

@section('title', 'Beranda')

@section('content')
<div class="mx-8 px-6 py-10 flex flex-col-reverse sm:flex-row items-center justify-between gap-8">
    <section class="text-left max-w-md sm:max-w-xl">
        <h1 class="text-3xl sm:text-6xl font-bold text-[#005EFF] leading-tight mb-4">
            Kehilangan<br>Barang?<br>
            <span class="text-blue-700">Temukan Solusinya<br>di <span class="text-blue-900">FILKOMFound!</span></span>
        </h1>

        <p class="text-gray-600 text-base mb-6">
            Platform kampus untuk bantu kamu menemukan kembali barang yang hilang, atau melaporkan barang yang kamu temukan — cepat dan mudah, hanya dalam beberapa klik.
        </p>

        <div class="flex flex-col sm:flex-row sm:justify-start sm:space-x-4 space-y-3 sm:space-y-0">
            
            {{-- DIUBAH: href sekarang mengarah ke halaman pengumuman (barang hilang) --}}
            <a href="{{ route('user.announcements.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full text-center font-medium shadow-sm transition">
                Laporkan Barang
            </a>
            
            {{-- Ini sudah benar, mengarah ke halaman daftar barang temuan --}}
            <a href="{{ route('user.items.index') }}" class="border border-orange-500 text-orange-500 hover:bg-orange-50 px-6 py-2 rounded-full text-center font-medium transition">
                Temukan Barang
            </a>
        </div>
    </section>

    <div class="flex justify-center">
        <img src="{{ asset('images/Gedung-Filkom.jpeg') }}" alt="Gedung FILKOM" class="rounded-full shadow-md w-40 h-40 sm:w-60 sm:h-60 object-cover" />
    </div>
</div>
@endsection