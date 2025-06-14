@extends('layouts.app')

@section('title', 'Daftar Barang Temuan')

@section('content')

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
            Daftar Barang Temuan
        </h1>

        {{-- Menampilkan notifikasi sukses setelah lapor/update/hapus barang --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            {{-- Bagian filter bisa dibiarkan kosong untuk saat ini --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            </div>

            <div>
                <a href="{{ route('user.items.create') }}" class="w-full sm:w-auto flex items-center justify-center bg-[#005EFF] hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition-colors">
                    Laporkan Temuan
                </a>
            </div>
        </div>

        {{-- Daftar Barang dari Database --}}
        <div class="space-y-4">
            @forelse ($items as $item)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-grow">
                        <p class="font-bold text-lg text-gray-900">{{ $item->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{-- Menggunakan Carbon untuk format tanggal dari database --}}
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
                            @if($item->status == 'Sudah Dikembalikan')
                                bg-green-100 text-green-800
                            @else
                                bg-orange-100 text-orange-800
                            @endif
                            rounded-full">
                            {{ $item->status }}
                        </span>
                        
                        {{-- Opsi Edit & Hapus hanya untuk pemilik postingan --}}
                        @if(auth()->id() == $item->user_id)
                            <a href="{{ route('user.items.edit', $item->id) }}" class="text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('user.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada barang yang ditemukan di database --}}
                <div class="bg-white text-center p-12 rounded-xl shadow-sm border border-gray-200">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Belum ada barang temuan yang dilaporkan.</p>
                </div>
            @endforelse
        </div>

        {{-- BARU: Link untuk navigasi halaman (pagination) --}}
        <div class="mt-8">
            {{ $items->links() }}
        </div>

    </div>
</div>
@endsection