<?php

namespace Database\Seeders;

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
        // Seed in order: Users first, then Products, then Carts and Orders
        $this->call([
            UserSeeder::class,      // Create users (including admin and test user)
            ProductSeeder::class,   // Create products
            CartSeeder::class,      // Create sample carts (optional, for testing)
            OrderSeeder::class,     // Create sample orders (for testing sales reports)
        ]);
    }
}
