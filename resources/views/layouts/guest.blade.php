<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FILKOMFound')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-gray-800 font-sans">

    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between px-6 py-4 bg-blue-600 shadow-md border-b border-blue-800">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="FILKOMFound" class="h-12 w-auto" />
        </div>

        <nav class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-8 text-sm text-white font-medium">
            <a href="{{ route('user.dashboard') }}" class="hover:text-yellow-300">Beranda</a>
            <a href="{{ route('user.items.index') }}" class="hover:text-yellow-300">Daftar Barang</a>
            <a href="#" class="hover:text-yellow-300">Pengumuman</a>
        </nav>

        <div class="space-x-2 text-sm mt-3 md:mt-0">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 border border-white text-white rounded-full hover:bg-blue-700">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition-colors">Daftar</a>
            @else
                <span class="text-white">{{ Auth::user()->name }}</span>
            @endguest
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
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
