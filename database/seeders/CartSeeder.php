<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please run UserSeeder and ProductSeeder first!');
            return;
        }

        // Create carts for some users (not all users need carts)
        $usersWithCarts = $users->random(min(3, $users->count()));

        foreach ($usersWithCarts as $user) {
            // Check if user already has a cart
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Add 1-3 random products to cart
            $itemCount = rand(1, 3);
            $selectedProducts = $products->random(min($itemCount, $products->count()));

            foreach ($selectedProducts as $product) {
                // Check if product already in cart
                $existingItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingItem) {
                    // Increment quantity if already exists
                    $existingItem->increment('quantity', rand(1, 2));
                } else {
                    // Create new cart item
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 2),
                    ]);
                }
            }
        }
    }
}
