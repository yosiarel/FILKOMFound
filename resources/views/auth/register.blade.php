<!-- resources/views/auth/register.blade.php -->
@extends('layouts.master')

@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen bg-blue-900 flex flex-col items-center justify-center px-6 py-10">
    <h1 class="text-white text-2xl font-bold mb-6">Daftar Akun</h1>

    <div class="bg-white w-full max-w-sm rounded-xl shadow-md p-6">
        {{-- Menampilkan pesan sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Menampilkan pesan error umum (jika ada error non-validasi) --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Menampilkan semua error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
                <p class="font-bold">Ada beberapa masalah dengan input Anda:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <input name="name" type="text" placeholder="Nama" value="{{ old('name') }}"
                       class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input name="nim" type="text" placeholder="NIM" value="{{ old('nim') }}"
                       class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nim') border-red-500 @enderror" required>
                @error('nim')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input name="email" type="email" placeholder="Email UB" value="{{ old('email') }}"
                       class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input name="password" type="password" placeholder="Password"
                       class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required autocomplete="new-password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Menambahkan input konfirmasi password seperti sebelumnya --}}
            <div>
                <input name="password_confirmation" type="password" placeholder="Konfirmasi Password"
                       class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required autocomplete="new-password">
            </div>

            <div class="text-sm text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            </div>

            <button type="submit" class="w-full bg-[#002C6A] text-white rounded-full py-2 font-semibold hover:bg-blue-800">
                Daftar
            </button>
        </form>
    </div>
</div>
@endsection
