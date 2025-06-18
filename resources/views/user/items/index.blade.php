@extends('layouts.master')

@section('title', 'Daftar Barang Temuan')

@section('content')

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
            Daftar Barang Temuan
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto"> {{-- Tambahkan w-full sm:w-auto --}}
                {{-- Bagian Filter dan Search (Hanya Tampilkan untuk Admin) --}}
                @if(Auth::check() && Auth::user()->hasRole('admin')) {{-- Asumsi method hasRole() ada di model User --}}
                    <div class="relative">
                        <button class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            <i class="fas fa-filter mr-2"></i> Sort <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        {{-- Dropdown untuk sort --}}
                    </div>
                    <div class="relative">
                        <button class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Posted at <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        {{-- Dropdown untuk Posted at --}}
                    </div>
                    <div class="relative">
                        <button class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Status <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        {{-- Dropdown untuk Status --}}
                    </div>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear All</button>
                @endif
                {{-- Input Search (Bisa untuk semua role, atau khusus admin) --}}
                <div class="relative flex-grow w-full"> {{-- Tambahkan w-full --}}
                    <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                {{-- Tombol Laporkan Temuan (Hanya Tampilkan untuk Mahasiswa/User Umum) --}}
                @if(Auth::check() && Auth::user()->hasRole('mahasiswa')) {{-- atau Auth::user()->role === 'mahasiswa' --}}
                    <a href="{{ route('user.items.create') }}" class="w-full sm:w-auto flex items-center justify-center bg-[#005EFF] hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition-colors">
                        Laporkan Temuan
                    </a>
                @endif
            </div>
        </div>

        {{-- Ini adalah bagian inti untuk menampilkan daftar barang --}}
        @if(Auth::check() && Auth::user()->hasRole('admin'))
            {{-- Tampilan Grid Card untuk Admin --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($items as $item)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        {{-- Gambar barang (hanya admin yang bisa lihat) --}}
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-48 object-cover object-center">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                Tidak ada gambar
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 mb-1 truncate">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d F Y') }} <span class="mx-1">&middot;</span> {{ $item->location }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Dilaporkan oleh: {{ $item->user->name ?? 'Anonim' }}
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="px-3 py-1 text-xs font-semibold
                                    @if($item->status == 'Sudah Dikembalikan') bg-green-100 text-green-800
                                    @elseif($item->status == 'Belum Diverifikasi') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'Sedang Diajukan') bg-blue-100 text-blue-800
                                    @else bg-orange-100 text-orange-800 @endif rounded-full">
                                    {{ $item->status }}
                                </span>
                                {{-- Tombol atau aksi khusus Admin untuk tiap item --}}
                                <div class="flex gap-2 ml-2">
                                    {{-- Contoh tombol verifikasi/detail --}}
                                    <a href="{{ route('admin.verifications.show', $item->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail/Verifikasi">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- Tombol edit/delete --}}
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="text-gray-600 hover:text-gray-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200 col-span-full">
                        <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-semibold">Belum ada barang temuan yang dilaporkan.</p>
                    </div>
                @endforelse
            </div>
        @else
            {{-- Tampilan Daftar/List untuk Mahasiswa/Pengguna Umum (yang sudah ada) --}}
            <div class="space-y-4">
                @forelse ($items as $item)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="item-header p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-gray-50 transition rounded-xl" data-target="#detail-{{ $item->id }}">
                            <div class="flex-grow">
                                <p class="font-bold text-lg text-gray-900">{{ $item->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->found_date)->translatedFormat('d F Y') }}
                                    <span class="mx-1">&middot;</span>
                                    {{ $item->location }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Dilaporkan oleh: {{ $item->user->name ?? 'Anonim' }}
                                </p>
                            </div>
                            <div class="w-full sm:w-auto flex-shrink-0 flex items-center gap-4">
                                <span class="px-4 py-1.5 text-sm font-semibold
                                    @if($item->status == 'Sudah Dikembalikan') bg-green-100 text-green-800 @else bg-orange-100 text-orange-800 @endif rounded-full">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>

                        <div id="detail-{{ $item->id }}" class="item-detail hidden px-4 pb-4 border-t border-gray-200">
                            <div class="py-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Deskripsi Barang:</h4>
                                <p class="text-gray-600 text-sm whitespace-pre-line">{{ $item->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>

                            @if ($item->image)
                                {{-- Gambar tidak ditampilkan untuk non-admin --}}
                                <div class="mt-4">
                                    <p class="text-gray-500 text-sm">Gambar tidak ditampilkan untuk peran ini.</p>
                                </div>
                            @endif

                            @if(Auth::check() && Auth::user()->hasRole('mahasiswa') && auth()->id() != $item->user_id && $item->status == 'Belum Dikembalikan')
                            <div class="flex justify-center pt-2 pb-4">
                                <form action="{{ route('user.claims.store', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-full shadow-md transition-colors">
                                        Ajukan klaim
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                        <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-semibold">Belum ada barang temuan yang dilaporkan.</p>
                    </div>
                @endforelse
            </div>
        @endif

        <div class="mt-8">
            {{ $items->links() }}
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pastikan script hanya berjalan jika ada item-header (untuk tampilan non-admin)
    const itemHeaders = document.querySelectorAll('.item-header');
    if (itemHeaders.length > 0) {
        itemHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const targetDetail = document.querySelector(targetId);

                document.querySelectorAll('.item-detail').forEach(detail => {
                    if (detail !== targetDetail) {
                        detail.classList.add('hidden');
                    }
                });

                targetDetail.classList.toggle('hidden');
            });
        });
    }
});
</script>

@endsection