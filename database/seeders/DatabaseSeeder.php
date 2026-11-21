<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Alya Pratama',
            'email' => 'alya@example.com',
        ]);

        User::factory()->create([
            'name' => 'Bima Saputra',
            'email' => 'bima@example.com',
        ]);

        User::factory()->create([
            'name' => 'Citra Lestari',
            'email' => 'citra@example.com',
        ]);

        $this->call(MediaSeeder::class);
    }
}
