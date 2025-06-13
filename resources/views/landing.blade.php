@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')
<section class="px-6 py-10 text-left max-w-md mx-auto sm:max-w-xl">
    <!-- Judul -->
    <h1 class="text-3xl sm:text-4xl font-bold text-blue-700 leading-tight mb-4">
        Kehilangan<br>Barang?<br>
        <span class="text-blue-700">Temukan Solusinya<br>di <span class="text-blue-900">FILKOMFound!</span></span>
    </h1>

    <!-- Deskripsi -->
    <p class="text-gray-600 text-base mb-6">
        Platform kampus untuk bantu kamu menemukan kembali barang yang hilang, atau melaporkan barang yang kamu temukan — cepat dan mudah, hanya dalam beberapa klik.
    </p>

    <!-- Tombol aksi -->
    <div class="flex flex-col sm:flex-row sm:justify-start sm:space-x-4 space-y-3 sm:space-y-0">
        <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full text-center font-medium shadow-sm transition">
            Laporkan Barang
        </a>
        <a href="{{ route('user.items.index') }}" class="border border-orange-500 text-orange-500 hover:bg-orange-50 px-6 py-2 rounded-full text-center font-medium transition">
            Temukan Barang
        </a>
    </div>

    <!-- Gambar / elemen ilustrasi (opsional) -->
    <div class="mt-10 flex justify-center">
        <img src="{{ asset('images/gedung-filkom.jpg') }}" alt="Gedung FILKOM" class="rounded-lg shadow-md w-64 sm:w-80" />
    </div>
</section>
@endsection
