@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen bg-[#002C6A] flex flex-col items-center justify-center px-6 py-10">
    <h1 class="text-white text-2xl font-bold mb-6">Daftar Akun</h1>

    <div class="bg-white w-full max-w-sm rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input name="name" type="text" placeholder="Nama" class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input name="nim" type="text" placeholder="NIM" class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input name="email" type="email" placeholder="Email UB" class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input name="password" type="password" placeholder="Password" class="w-full border border-blue-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>

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
