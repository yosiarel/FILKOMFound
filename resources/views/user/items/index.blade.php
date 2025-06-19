@extends('layouts.master')

@section('title', 'Daftar Barang Temuan')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Daftar Barang Temuan</h1>
        <p class="text-gray-600 mb-6">
            @if(Auth::check() && Auth::user()->role === 'admin')
                Kelola semua barang temuan yang dilaporkan di platform ini.
            @else
                Temukan barang hilangmu yang dilaporkan oleh pengguna lain di sini.
            @endif
        </p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- BAGIAN FILTER --}}
        <form action="{{ route('user.items.index') }}" method="GET" class="mb-6">
            <div x-data="{ searchFocused: '{{ request('search') }}' !== '' }" class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="relative flex-grow">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                    <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}"
                           @focus="searchFocused = true" @blur="if ($el.value === '') searchFocused = false"
                           class="relative w-full pl-10 pr-20 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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

            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
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

                <div>
                    <label for="found_date" class="text-sm font-semibold text-gray-700 mr-2">Tanggal Ditemukan:</label>
                    <input type="date" name="found_date" id="found_date" value="{{ request('found_date') }}"
                           class="border border-gray-300 rounded-lg px-2 py-1 text-sm"
                           onchange="this.form.submit()">
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-700">Status:</span>
                    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        <option value="found" @selected(request('status') == 'found')>Tersedia</option>
                        <option value="claimed" @selected(request('status') == 'claimed')>Diklaim</option>
                        <option value="returned" @selected(request('status') == 'returned')>Dikembalikan</option>
                    </select>
                </div>
                
                @if(request()->hasAny(['search', 'status', 'sort', 'found_date']))
                    <a href="{{ route('user.items.index') }}" class="text-sm text-red-500 hover:underline">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        {{-- ====================================================== --}}
        {{-- ============ MULAI PEMISAHAN TAMPILAN ================ --}}
        {{-- ====================================================== --}}

        @if(Auth::check() && Auth::user()->role === 'admin')

            {{-- TAMPILAN GRID UNTUK ADMIN --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @forelse ($items as $item)
                    <a href="{{ route('user.admin.verifications.show', $item->id) }}" class="block bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden group hover:shadow-lg hover:border-blue-500 transition-all duration-300">
                        <div class="relative">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}"
                                     class="w-full h-40 object-cover object-center">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-md text-gray-900 truncate group-hover:text-blue-600 transition">{{ $item->item_name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d M Y') }} &middot; {{ $item->location }}
                            </p>
                            <div class="mt-2">
                                @php
                                    $statusText = $item->admin_formatted_status;
                                    $bgColor = 'bg-gray-100 text-gray-800'; // Default
                                    if ($statusText == 'Belum Diverifikasi') { $bgColor = 'bg-red-100 text-red-800'; }
                                    elseif ($statusText == 'Sedang Diajukan') { $bgColor = 'bg-yellow-100 text-yellow-800'; }
                                    elseif ($statusText == 'Belum Dikembalikan') { $bgColor = 'bg-blue-100 text-blue-800'; }
                                    elseif ($statusText == 'Sudah Dikembalikan') { $bgColor = 'bg-green-100 text-green-800'; }
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bgColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-white text-center p-12 rounded-xl shadow-sm border">
                        <p class="text-gray-500 font-semibold">Tidak ada barang temuan yang cocok dengan filter Anda.</p>
                    </div>
                @endforelse
            </div>

        @else

            {{-- PERBAIKAN: TAMPILAN ACCORDION UNTUK MAHASISWA --}}
            <div class="space-y-4">
                @forelse ($items as $item)
                    <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm border border-gray-200">
                        {{-- Header Card (yang bisa diklik) --}}
                        <div @click="open = !open" class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-gray-50 transition rounded-t-xl">
                            <div class="flex-grow">
                                <p class="font-bold text-lg text-gray-900">{{ $item->item_name }}</p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt fa-fw mr-1"></i> {{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d F Y') }}
                                    <span class="mx-2 hidden sm:inline">&middot;</span>
                                    <i class="fas fa-map-marker-alt fa-fw mr-1 mt-2 sm:mt-0"></i> {{ $item->location }}
                                </p>
                            </div>
                            <div class="w-full sm:w-auto flex-shrink-0 flex items-center gap-4">
                                <span class="px-4 py-1.5 text-sm font-semibold
                                    @if($item->status == 'returned') bg-green-100 text-green-800
                                    @else bg-orange-100 text-orange-800 @endif rounded-full">
                                    {{ $item->formatted_status }}
                                </span>
                                {{-- Panah Indikator --}}
                                <i class="fas fa-chevron-down transform transition-transform" :class="{'rotate-180': open}"></i>
                            </div>
                        </div>

                        {{-- Detail Card (yang bisa expand/collapse) --}}
                        <div x-show="open" x-transition class="px-4 pb-4 border-t border-gray-200">
                            <div class="py-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Deskripsi Barang:</h4>
                                <p class="text-gray-600 text-sm whitespace-pre-line">{{ $item->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>
                            @if(Auth::check() && Auth::user()->role === 'mahasiswa' && Auth::id() != $item->user_id && $item->status == 'found')
                            <div class="flex justify-center pt-2 pb-4">
                                <form action="{{ route('user.claims.store', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-full shadow-md transition-colors">
                                        Ajukan Klaim
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white text-center p-12 rounded-xl shadow-sm border">
                        <p class="text-gray-500 font-semibold">Belum ada barang temuan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

        @endif
        
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>
</div>