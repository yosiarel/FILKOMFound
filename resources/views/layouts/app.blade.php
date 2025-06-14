<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FILKOMFound')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    
    {{-- TAMBAHAN: Link untuk ikon (seperti ikon hamburger & footer) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body style="margin: 0;padding: 0; font-family: 'Poppins', sans-serif;" class="bg-gray-50">
    
    <header class="bg-white sticky top-0 z-50 shadow-sm font-poppins">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('user.dashboard') }}" class="flex items-center">
                {{-- DIUBAH: Path asset yang benar --}}
                <img src="{{ asset('images/logo.png') }}" alt="FILKOMFound Logo" class="h-12 w-auto" />
            </a>

            <nav class="hidden lg:flex space-x-10 text-base font-medium text-gray-800">
                <a href="{{ route('user.dashboard') }}" class="hover:text-orange-500 transition">Beranda</a>
                <a href="{{ route('user.items.index') }}" class="hover:text-orange-500 transition">Daftar Barang</a>
                
                {{-- DIUBAH: Link Pengumuman sekarang mengarah ke route yang benar --}}
                <a href="{{ route('user.announcements.index') }}" class="hover:text-orange-500 transition">Pengumuman</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-full font-semibold hover:bg-blue-50 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition">Daftar</a>
                @else
                    {{-- DIUBAH: Tampilan untuk user yang sudah login dengan tombol Logout --}}
                    <span class="text-gray-800 font-semibold">{{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="ml-4 px-5 py-2 border border-red-500 text-red-500 rounded-full hover:bg-red-100 transition font-semibold text-sm">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                @endguest
            </div>

            <button id="mobile-menu-button" class="lg:hidden text-2xl text-gray-800 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 px-6 py-4 space-y-3 text-base">
            <a href="{{ route('user.dashboard') }}" class="block text-gray-800 hover:text-orange-500">Beranda</a>
            <a href="{{ route('user.items.index') }}" class="block text-gray-800 hover:text-orange-500">Daftar Barang</a>
            
            {{-- DIUBAH: Link Pengumuman di menu mobile juga diperbaiki --}}
            <a href="{{ route('user.announcements.index') }}" class="block text-gray-800 hover:text-orange-500">Pengumuman</a>

            <div class="pt-4 border-t border-gray-200">
                @guest
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-2 mb-2 border-2 border-blue-600 text-blue-600 rounded-full font-semibold hover:bg-blue-50">Masuk</a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-5 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700">Register</a>
                @else
                    <span class="block text-center font-semibold text-gray-800 mb-3">{{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                       class="block w-full text-center px-5 py-2 border border-red-500 text-red-500 rounded-full hover:bg-red-100 font-semibold">
                        Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                @endguest
            </div>
        </div>
    </header>

    {{-- DIUBAH: Typo 'mmin-h-screen' diperbaiki --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="w-full bg-[#002C6A] py-8 px-6 text-sm text-white">
        <div class="container mx-auto">
            <h3 class="mb-1 text-lg font-bold">Fakultas Ilmu Komputer (FILKOM)</h3>
            <h3 class="text-sm">Universitas Brawijaya</h3>
            <p class="text-sm">Jl. Veteran No. 8, Malang, Jawa Timur, Indonesia – 65145</p>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <h4 class="text-[#EA8255] font-bold">PHONE</h4>
                    <p>(0341) 577-911</p>
                </div>
                <div>
                    <h4 class="text-[#EA8255] font-bold">FAX</h4>
                    <p>(0341) 577-911</p>
                </div>
                <div>
                    <h4 class="text-[#EA8255] font-bold">EMAIL</h4>
                    <p>filkom@ub.ac.id</p>
                </div>
            </div>

            <div class="mt-6 border-t border-blue-800 pt-6 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-xs">&copy; {{ date('Y') }} FILKOMFound. All rights reserved.</p>
                <div class="mt-4 sm:mt-0 flex space-x-4">
                    <a href="#" class="hover:text-orange-500"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="hover:text-orange-500"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="hover:text-orange-500"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="hover:text-orange-500"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Script untuk menu mobile --}}
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