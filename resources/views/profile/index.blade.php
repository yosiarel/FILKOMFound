@extends('layouts.master')

@section('title', 'Profil Pengguna')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: INFO PENGGUNA --}}
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto text-4xl font-bold mb-4">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-gray-500 font-mono">{{ Auth::user()->nim }}</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: MENU & AKSI --}}
            <div class="md:col-span-2">
                
                {{--- BAGIAN KHUSUS ADMIN ---}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="bg-white p-6 rounded-xl shadow-sm border mb-8">
                    <h3 class="font-bold text-lg text-gray-800 border-b pb-3 mb-4">Pusat Kontrol Admin</h3>
                    <div class="space-y-3">
                        {{-- --- LINK BARU DI SINI --- --}}
                        <a href="{{ route('user.admin.verifications.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-red-50 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-inbox text-red-600 fa-lg"></i>
                                <span>Verifikasi Laporan Temuan</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('user.admin.claims.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-clipboard-check text-blue-600 fa-lg"></i>
                                <span>Verifikasi Pengajuan Klaim</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('user.admin.reports.announcements.index') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-yellow-50 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-flag text-yellow-600 fa-lg"></i>
                                <span>Daftar Aduan Pengumuman</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('user.admin.claims.history') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-history text-green-600 fa-lg"></i>
                                <span>Riwayat Pengembalian Barang</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                </div>
                @endif
                
                {{-- ... bagian menu umum tidak berubah ... --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h3 class="font-bold text-lg text-gray-800 border-b pb-3 mb-4">Aktivitas & Pengaturan</h3>
                    <div class="space-y-3">
                        <a href="{{ route('user.profile.foundhistory') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-box-open text-gray-600"></i>
                                <span>Riwayat Laporan Temuan</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('user.profile.announcementshistory') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-bullhorn text-gray-600"></i>
                                <span>Riwayat Laporan Kehilangan</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('user.profile.claimedhistory') }}" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-hand-paper text-gray-600"></i>
                                <span>Riwayat Klaim</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                    <div class="mt-6 pt-6 border-t">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:bg-red-50 p-3 rounded-lg transition">
                                <i class="fas fa-sign-out-alt fa-fw mr-2"></i>Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection