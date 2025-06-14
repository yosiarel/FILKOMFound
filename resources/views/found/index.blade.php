@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Barang Temuan</h1>

    @foreach ($items as $item)
    <div x-data="{ open: false }" class="mb-4 bg-white rounded-2xl shadow p-4">
        <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
            <div>
                <h2 class="font-semibold text-lg">{{ $item->name }}</h2>
                <p class="text-sm text-gray-600">{{ $item->date_found->format('d F Y') }} — {{ $item->location }}</p>
            </div>
            <span class="text-sm font-bold text-gray-500">{{ $item->status }}</span>
        </div>

        <div x-show="open" x-transition class="mt-3 border-t pt-3 text-sm text-gray-700">
            <p><strong>Deskripsi:</strong> {{ $item->description }}</p>

            <form action="{{ route('claims.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Ajukan Klaim</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
