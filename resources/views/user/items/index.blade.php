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
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            </div>
            <div>
                <a href="{{ route('user.items.create') }}" class="w-full sm:w-auto flex items-center justify-center bg-[#005EFF] hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition-colors">
                    Laporkan Temuan
                </a>
            </div>
        </div>

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
                        
                        {{-- BLOK KODE UNTUK MENAMPILKAN FOTO DIHAPUS DARI SINI --}}
                        
                        @if(auth()->id() != $item->user_id && $item->status == 'Belum Dikembalikan')
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

        <div class="mt-8">
            {{ $items->links() }}
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemHeaders = document.querySelectorAll('.item-header');

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
});
</script>

@endsection