<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FILKOMFound')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body>

    <!-- Header (navbar) -->
    <header class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center">
        <!-- Logo + Judul -->
        <div class="flex items-left space-x-2">
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
    <main class="mmin-h-screen px-4 sm:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
     <footer class="w-full bg-[#002C6A] p-full text-sm">
        <h3 class="mb-1 text-[#FFFF] text-lg font-bold">Fakultas Ilmu Komputer (FILKOM)</h3>
        <h3 class="text-sm text-[#FFFF]">Universitas Brawijaya</h3>
        <p class="text-sm text-[#FFFF]">Jl. Veteran No. 8, Malang, Jawa Timur, Indonesia – 65145</p>

        <div class="mt-4">
            <h4 class="mb-2 text-[#EA8255]">PHONE</h4>
            <p class="text-[#FFFF]">(0341) 577-911</p>
        </div>

        <div class="mt-2">
            <h4 class="mb-2 text-[#EA8255]">FAX</h4>
            <p class="text-[#FFFF]">(0341) 577-911</p>
        </div>

        <div class="mt-2">
            <h4 class="mb-2 text-[#EA8255]">EMAIL</h4>
            <p class="text-[#FFFF]">filkom@ub.ac.id</p>
        </div>

        <p class="mt-2 text-[#FFFF] items-center text-xs ">&copy; {{ date('Y') }} FILKOMFound. All rights reserved.</p>

        <!-- Add social media icons here -->
        <div class="mt-4 flex justify-center space-x-4">
            <a href="#" class="text-[#FFFF]  hover:text-orange-500"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-[#FFFF]  hover:text-orange-500"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-[#FFFF]  hover:text-orange-500"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-[#FFFF]  hover:text-orange-500"><i class="fab fa-youtube"></i></a>
        </div>
    </footer>

</body>
</html>
