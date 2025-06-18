<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat pengguna terlebih dahulu
        
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@filkom.ub.ac.id'],
            [
                'name' => 'Admin FILKOM',
                'nim' => '000000000000000', // Beri NIM placeholder untuk admin
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Contoh user mahasiswa 1
        User::firstOrCreate(
            ['email' => 'mhs1@student.ub.ac.id'],
            [
                'name' => 'Mahasiswa Satu',
                'nim' => '235150200111001',
                'role' => 'mahasiswa',
                'password' => Hash::make('password'),
            ]
        );

        // Contoh user mahasiswa 2
        User::firstOrCreate(
            ['email' => 'mhs2@student.ub.ac.id'],
            [
                'name' => 'Mahasiswa Dua',
                'nim' => '235150401111002',
                'role' => 'mahasiswa',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Panggil seeder lain setelah pengguna dibuat
        $this->call([
            ItemSeeder::class,
            // Anda bisa menambahkan seeder lain di sini jika ada,
            // contoh: AnnouncementSeeder::class
        ]);
        
    }
}
