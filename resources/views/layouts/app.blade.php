<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FILKOMFound')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Header (navbar) -->
    <header class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center">
        <!-- Logo + Judul -->
        <div class="flex items-center space-x-2">
            <img src="{{ asset('public/images/logo.png') }}" alt="FILKOMFound" class="h-1 w-auto" />
        </div>

        <!-- Navigasi -->
        <nav class="flex items-center space-x-6 text-sm text-gray-700 font-medium">
            <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600">Beranda</a>
            <a href="{{ route('user.items.index') }}" class="hover:text-blue-600">Daftar Barang</a>
            <a href="#" class="hover:text-blue-600">Pengumuman</a>
        </nav>

        <!-- Profil pengguna -->
        <div class="text-sm font-medium text-gray-600">
            {{ Auth::user()->name }}
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="min-h-screen px-4 sm:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#002C6A] text-white text-xs px-6 py-4">
        <p class="mb-1 text-white font-semibold">Fakultas Ilmu Komputer (FILKOM)</p>
        <p class="text-sm">Universitas Brawijaya</p>
        <p class="text-sm">Jl. Veteran No. 8, Malang, Jawa Timur, Indonesia – 65145</p>
        <p class="mt-2 text-xs">&copy; {{ date('Y') }} FILKOMFound. All rights reserved.</p>
    </footer>

</body>
</html>
