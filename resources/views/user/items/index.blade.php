@extends('layouts.master')

@section('title', 'Daftar Barang Temuan')

{{-- Pastikan Alpine.js ada di layout master Anda, atau tambahkan di sini --}}
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Daftar Barang Temuan</h1>
        <p class="text-gray-600 mb-6">Temukan barang hilangmu yang dilaporkan oleh pengguna lain di sini.</p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('user.items.index') }}" method="GET" class="mb-6">
            {{-- Search Bar Interaktif dengan Alpine.js --}}
            <div x-data="{ searchFocused: '{{ request('search') }}' !== '' }" class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="relative flex-grow">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                    <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}"
                           @focus="searchFocused = true" @blur="if ($el.value === '') searchFocused = false"
                           class="relative w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    {{-- Tombol 'Cari' hanya muncul saat search bar di-fokus atau berisi --}}
                    <button type="submit" x-show="searchFocused" x-transition
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 text-white font-semibold px-4 py-1 rounded-lg hover:bg-blue-700 text-sm">
                        Cari
                    </button>
                </div>
                @if(Auth::check() && Auth::user()->role === 'mahasiswa')
                    <a href="{{ route('user.items.create') }}" class="flex-shrink-0 text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition">
                        Laporkan Temuan
                    </a>
                @endif
            </div>

            {{-- Filter Lanjutan --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                {{-- Tombol Sort dengan Panah Dinamis --}}
                @php
                    $currentSort = request('sort', 'desc');
                    $nextSort = $currentSort === 'desc' ? 'asc' : 'desc';
                @endphp
                <a href="{{ route('user.items.index', array_merge(request()->query(), ['sort' => $nextSort])) }}" class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-blue-600">
                    <span>Urutkan Tanggal</span>
                    @if($currentSort === 'desc')
                        <i class="fas fa-arrow-down"></i>
                    @else
                        <i class="fas fa-arrow-up"></i>
                    @endif
                </a>

                {{-- Filter Tanggal --}}
                <div>
                    <label for="found_date" class="text-sm font-semibold text-gray-700 mr-2">Tanggal Ditemukan:</label>
                    <input type="date" name="found_date" id="found_date" value="{{ request('found_date') }}"
                           class="border border-gray-300 rounded-lg px-2 py-1 text-sm"
                           onchange="this.form.submit()"> {{-- Otomatis submit saat tanggal diubah --}}
                </div>
                
                {{-- Filter Status --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-700">Status:</span>
                    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-2 py-1 text-sm">
                        <option value="">Semua</option>
                        <option value="found" @selected(request('status') == 'found')>Tersedia</option>
                        <option value="claimed" @selected(request('status') == 'claimed')>Diklaim</option>
                    </select>
                </div>
                
                {{-- Tombol Reset --}}
                @if(request()->hasAny(['search', 'status', 'sort', 'found_date']))
                    <a href="{{ route('user.items.index') }}" class="text-sm text-red-500 hover:underline">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        {{-- Sisa kode untuk menampilkan grid barang (tidak ada perubahan) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($items as $item)
                {{-- Card Item --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden group">
                    {{-- ... Konten Card ... --}}
                </div>
            @empty
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200 col-span-full">
                    <p class="text-gray-500 font-semibold">Tidak ada barang temuan yang cocok.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection