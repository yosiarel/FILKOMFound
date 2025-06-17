@extends('layouts.master')

@section('title', 'Buat Pengumuman Barang Hilang')

@section('content')

@php
    // Logika ini akan berguna saat kita membuat fitur 'edit' nanti
    $isEdit = isset($announcement);
    $formAction = $isEdit ? route('user.announcements.update', $announcement->id) : route('user.announcements.store');
@endphp

<div class="bg-orange-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-md">
            
            <!-- Header: Tombol Kembali dan Judul -->
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('user.announcements.index') }}" class="text-gray-500 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left text-xl sm:text-2xl"></i>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Buat Pengumuman
                </h1>
            </div>

            {{-- Menampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Barang</label>
                            <input type="text" name="name" id="name" placeholder="Contoh: Dompet Coklat"
                                   value="{{ old('name', $isEdit ? $announcement->name : '') }}"
                                   class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required>
                        </div>
                        <div>
                            <label for="lost_time" class="block text-sm font-semibold text-gray-700 mb-2">Perkiraan Waktu Terakhir</label>
                            <input type="datetime-local" name="lost_time" id="lost_time"
                                   value="{{ old('lost_time', $isEdit ? ($announcement->lost_time ? $announcement->lost_time->format('Y-m-d\TH:i') : '') : now()->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required>
                        </div>
                         <div>
                            <label for="estimated_location" class="block text-sm font-semibold text-gray-700 mb-2">Perkiraan Lokasi Terakhir</label>
                            <input type="text" name="estimated_location" id="estimated_location" placeholder="Contoh: Kantin Gedung F"
                                   value="{{ old('estimated_location', $isEdit ? $announcement->estimated_location : '') }}"
                                   class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-6">
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Barang</label>
                            <textarea name="description" id="description" rows="5"
                                      placeholder="Isi deskripsi mengenai ciri-ciri barang, merk, warna, dll."
                                      class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition">{{ old('description', $isEdit ? $announcement->description : '') }}</textarea>
                        </div>
                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Foto Barang (Jika Ada)</label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-orange-300 px-6 py-10">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-orange-400"></i>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                        <label for="image-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-orange-600 focus-within:outline-none hover:text-orange-500">
                                            <span>Pilih file</span>
                                            <input id="image-upload" name="image" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau drag & drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF hingga 2MB</p>
                                    <p id="file-name-display" class="text-sm text-green-600 mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 flex justify-end">
                    <a href="{{ route('user.announcements.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition mr-4">
                        Batal
                    </a>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition-colors">
                        Umumkan Kehilangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script sederhana untuk menampilkan nama file yang dipilih
document.getElementById('image-upload').addEventListener('change', function(event) {
    const fileName = event.target.files[0] ? event.target.files[0].name : '';
    if (fileName) {
        document.getElementById('file-name-display').textContent = 'File dipilih: ' + fileName;
    } else {
        document.getElementById('file-name-display').textContent = '';
    }
});
</script>

@endsection
