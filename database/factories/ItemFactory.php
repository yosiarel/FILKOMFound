<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil user mahasiswa secara acak untuk dijadikan pelapor
        $user = User::where('role', 'mahasiswa')->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'item_name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'location' => 'Gedung ' . fake()->randomElement(['F', 'G', 'H', 'C', 'D']),
            'found_date' => fake()->dateTimeThisMonth(),
            'image' => null,
            'status' => 'found',
            // Ini adalah baris kunci yang membuat item langsung terverifikasi
            'verified_at' => now(), 
        ];
    }
}
