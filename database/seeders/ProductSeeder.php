<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'price' => 79.99,
                'stock_quantity' => 50,
            ],
            [
                'name' => 'USB-C Charging Cable (2m)',
                'price' => 14.99,
                'stock_quantity' => 200,
            ],
            [
                'name' => 'Mechanical Gaming Keyboard',
                'price' => 129.99,
                'stock_quantity' => 35,
            ],
            [
                'name' => 'Ergonomic Wireless Mouse',
                'price' => 49.99,
                'stock_quantity' => 75,
            ],
            [
                'name' => 'HD Webcam 1080p',
                'price' => 59.99,
                'stock_quantity' => 40,
            ],
            [
                'name' => 'Portable External SSD 500GB',
                'price' => 89.99,
                'stock_quantity' => 25,
            ],
            [
                'name' => 'Monitor Stand with USB Hub',
                'price' => 69.99,
                'stock_quantity' => 30,
            ],
            [
                'name' => 'Noise Cancelling Earbuds',
                'price' => 149.99,
                'stock_quantity' => 60,
            ],
            [
                'name' => 'Laptop Cooling Pad',
                'price' => 34.99,
                'stock_quantity' => 45,
            ],
            [
                'name' => 'Smart Power Strip',
                'price' => 44.99,
                'stock_quantity' => 80,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
