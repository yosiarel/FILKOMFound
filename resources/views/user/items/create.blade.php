@extends('layouts.app')

{{-- Menentukan judul halaman secara dinamis --}}
@section('title', isset($item) ? 'Edit Barang Temuan' : 'Laporkan Barang Temuan')

@section('content')

@php
    // Cek apakah form dalam mode 'edit' atau 'create'
    $isEdit = isset($item);

    // Menentukan route dan method form secara dinamis
    $formAction = $isEdit ? route('user.items.update', $item->id) : route('user.items.store');
@endphp

<div class="bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
            
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
                {{ $isEdit ? 'Edit Barang Temuan' : 'Laporkan Barang Temuan' }}
            </h1>

            {{-- Menampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Barang</label>
                        <input type="text" name="name" id="name" placeholder="Contoh: Earphone merk A"
                               value="{{ old('name', $isEdit ? $item->name : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="found_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Ditemukan</label>
                        <input type="date" name="found_date" id="found_date"
                               value="{{ old('found_date', $isEdit ? ($item->found_date ? $item->found_date->format('Y-m-d') : '') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Ditemukan</label>
                        <input type="text" name="location" id="location" placeholder="Contoh: Gazebo F"
                               value="{{ old('location', $isEdit ? $item->location : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                                  placeholder="Deskripsikan ciri-ciri barang, warna, kondisi, dll."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', 'value', $isEdit ? $item->description : '') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Barang</label>
                        <input type="file" name="image" id="image"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        
                        @if ($isEdit && $item->image)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg shadow-md">
                                <p class="text-xs text-gray-500 mt-2">Pilih file baru jika Anda ingin menggantinya.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('user.items.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2 bg-[#005EFF] hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-colors">
                        {{ $isEdit ? 'Update Barang' : 'Laporkan Barang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection