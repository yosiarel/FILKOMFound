<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item; // <-- Tambahkan ini

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data item lama agar tidak menumpuk setiap kali seeder dijalankan
        Item::truncate();

        // Panggil ItemFactory untuk membuat 25 data item baru
        Item::factory(25)->create();
    }
}