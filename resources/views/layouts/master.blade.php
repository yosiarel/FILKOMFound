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
    {{-- Anda perlu menambahkan link Font Awesome agar ikon menu mobile tampil --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite('resources/css/app.css')
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}} {{-- Pastikan ini dihapus jika sudah pakai @vite --}}
</head>
<body style="margin: 0;padding: 0; font-family: 'Poppins', sans-serif;">
    <header class="bg-white sticky top-0 z-50 shadow-sm font-poppins">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="flex items-center">
      <img src="{{ asset('images/logo.png') }}" alt="FILKOMFound Logo" class="h-12 w-auto" />
    </div>

    <nav class="hidden lg:flex space-x-10 text-base font-medium text-black">
      <a href="{{ route('user.dashboard') }}"
        class="{{ Request::routeIs('user.dashboard') ? 'text-[#EA8255] font-semibold' : 'hover:text-orange-400 transition' }}">
        Beranda
      </a>
      <a href="{{ route('user.items.index') }}"
        class="{{ Request::routeIs('user.items.*') ? 'text-[#EA8255] font-semibold' : 'hover:text-orange-400 transition' }}">
        Daftar Barang
      </a>
      <a href="{{ route('user.announcements.index') }}"
        class="{{ Request::routeIs('user.announcements.*') ? 'text-[#EA8255] font-semibold' : 'hover:text-orange-400 transition' }}">
        Pengumuman
      </a>
    </nav>
    <div class="hidden lg:flex items-center space-x-4">
      @guest
        <a href="{{ route('login') }}"
          class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-full font-semibold hover:bg-blue-50 transition">
          Masuk
        </a>
        <a href="{{ route('register') }}"
          class="px-6 py-2 bg-[#007BFF] border-white drop-shadow-xl text-white rounded-full font-semibold hover:bg-blue-700 transition">
          Daftar
        </a>
      @else
        {{-- BAGIAN YANG DIUBAH UNTUK TAMPILAN PROFIL (LOGOUT DIHILANGKAN) --}}
        <a href="{{ route('user.profile.index') }}" class="flex items-center space-x-2 text-[#002C6A] hover:text-[#EA8255] transition">
            <span class="font-semibold">{{ Auth::user()->name }}</span>
            <i class="fas fa-user-circle text-2xl"></i>
        </a>
        {{-- Tombol Logout telah dihilangkan dari sini --}}
        {{-- Pastikan ada opsi logout di halaman profil itu sendiri jika diperlukan --}}
      @endguest
    </div>

    <button id="mobile-menu-button" class="lg:hidden text-2xl text-[#002C6A] focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 px-6 py-4 space-y-2 text-base">
    <a href="{{ route('user.dashboard') }}" class="block text-[#002C6A] hover:text-[#EA8255]">Beranda</a>
    <a href="{{ route('user.items.index') }}" class="block text-[#002C6A] hover:text-[#EA8255]">Daftar Barang</a>
    <a href="{{ route('user.announcements.index') }}" class="block text-[#002C6A] hover:text-[#EA8255]">Pengumuman</a>

    <div class="pt-4 border-t border-gray-200">
      @guest
        <a href="{{ route('login') }}"
          class="block w-full text-center px-5 py-2 mb-2 border-2 border-blue-600 text-blue-600 rounded-full font-semibold hover:bg-blue-50">
          Masuk
        </a>
        <a href="{{ route('register') }}"
          class="block w-full text-center px-5 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700">
          Register
        </a>
      @else
        {{-- BAGIAN YANG DIUBAH UNTUK TAMPILAN PROFIL MOBILE (LOGOUT DIHILANGKAN) --}}
        <a href="{{ route('user.profile.index') }}" class="block text-center font-semibold text-[#002C6A] hover:text-[#EA8255] mb-2">
            <i class="fas fa-user-circle text-2xl mr-2 align-middle"></i> {{ Auth::user()->name }}
        </a>
        {{-- Tombol Logout telah dihilangkan dari sini --}}
      @endguest
    </div>
  </div>
</header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-[#002C6A] text-xs py-8">
        <div class="container mx-auto px-6">
        <h3 class="text-[#FFFF] text-lg font-semibold">Fakultas Ilmu Komputer (FILKOM)</h3>
        <h3 class="text-sm text-[#FFFF]">Universitas Brawijaya</h3>
        <p class="text-sm text-[#FFFF]">Jl. Veteran No. 8, Malang, Jawa Timur, Indonesia – 65145</p>

        <div class="mt-4">
            <h4 class="text-[#EA8255]">PHONE</h4>
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

        <div class="mt-4 flex justify-start space-x-10">
            <a href="https://www.facebook.com/filkombrawijaya/?_rdc=1&_rdr#" class="text-[#FFFF] hover:text-orange-500"><i class="fab fa-facebook"></i></a>
            <a href="https://x.com/filkomUB" class="text-[#FFFF] hover:text-orange-500"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/filkomub/" class="text-[#FFFF] hover:text-orange-500"><i class="fab fa-instagram"></i></a>
            <a href="https://www.youtube.com/c/FakultasIlmuKomputerUB" class="text-[#FFFF] hover:text-orange-500"><i class="fab fa-youtube"></i></a>
        </div>

        <p class="mt-2 text-[#FFFF] text-xs">© {{ date('Y') }}. Rights Reserved.</p>
        </div>
    </footer>

    {{-- Script untuk menu mobile, bisa diletakkan di sini atau di file JS terpisah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>