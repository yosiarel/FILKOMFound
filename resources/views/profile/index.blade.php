@extends('layouts.master') {{-- Menggunakan layout master Anda --}}

@section('title', 'Profil Pengguna') {{-- Judul halaman spesifik --}}

@section('content')
<div class="min-h-screen bg-[#D8E2F4] flex flex-col items-center py-8 font-poppins">
    {{-- Main Content - Profile Section --}}
    <div class="container mx-auto px-4 mt-8 w-full max-w-lg">
        {{-- Back Button and Page Title --}}
        <div class="flex items-center mb-6">
            <a href="{{ route('user.dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="text-xl font-semibold text-[#002C6A]">Profile</span>
            </a>
        </div>

        {{-- User Profile Card --}}
        <div class="bg-[#002C6A] text-white rounded-lg p-6 mb-8 shadow-md flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-gray-300 flex-shrink-0 overflow-hidden">
                {{-- Ganti dengan path gambar profil asli. Gunakan Auth::user()->profile_picture jika ada --}}
               <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-[#002C6A] p-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ Auth::user()->name ?? 'Nama Pengguna' }}</h2> {{-- Ambil nama dari user yang login --}}
                <p class="text-sm">{{ Auth::user()->email ?? 'email@student.ub.ac.id' }}</p> {{-- Ambil email dari user yang login --}}
                <p class="text-sm mt-1">{{ Auth::user()->nim ?? '235150200111888' }}</p> {{-- Asumsi ada kolom 'nim' --}}
            </div>
        </div>

        {{-- Navigation Links --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <a href="{{ route('user.profile.announcementshistory') }}" class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 -mx-6 px-6 transition duration-150 ease-in-out">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-100 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-gray-800 text-lg">Riwayat Laporan Kehilangan</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('user.profile.foundhistory') }}" class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 -mx-6 px-6 transition duration-150 ease-in-out">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-100 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-gray-800 text-lg">Riwayat Temuan</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('user.profile.claimedhistory') }}" class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 -mx-6 px-6 transition duration-150 ease-in-out">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-100 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-gray-800 text-lg">Riwayat Klaim</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="flex items-center justify-between w-full py-3 hover:bg-gray-50 -mx-6 px-6 focus:outline-none transition duration-150 ease-in-out">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="text-gray-800 text-lg">Log out</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection