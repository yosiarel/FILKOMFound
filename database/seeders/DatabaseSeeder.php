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
        // Admin account
        User::create([
            'name' => 'Admin FILKOM',
            'email' => 'admin@filkom.ub.ac.id',
            'nim' => null, // Admin tidak punya NIM
            'role' => 'admin',
            'password' => Hash::make('admin123'), // Gunakan hash untuk keamanan
        ]);

        // Contoh user mahasiswa
        User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'mhs1@ub.ac.id',
            'nim' => '235150200111058',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Mahasiswa 2',
            'email' => 'mhs2@ub.ac.id',
            'nim' => '235150401111052',
            'role' => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);
    }
}
