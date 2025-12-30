<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
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

        // Create orders for today (for testing daily sales report)
        $today = Carbon::today();
        
        // Create 5-10 orders for today
        for ($i = 0; $i < rand(5, 10); $i++) {
            $user = $users->random();
            $orderItems = [];
            $totalPrice = 0;

            // Each order has 1-4 items
            $itemCount = rand(1, 4);
            $selectedProducts = $products->random(min($itemCount, $products->count()));

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $quantity * $price;
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'created_at' => $today->copy()->addHours(rand(9, 22))->addMinutes(rand(0, 59)),
                'updated_at' => now(),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        // Create orders for yesterday (to show historical data)
        $yesterday = Carbon::yesterday();
        
        for ($i = 0; $i < rand(3, 7); $i++) {
            $user = $users->random();
            $orderItems = [];
            $totalPrice = 0;

            $itemCount = rand(1, 3);
            $selectedProducts = $products->random(min($itemCount, $products->count()));

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2);
                $price = $product->price;
                $subtotal = $quantity * $price;
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'created_at' => $yesterday->copy()->addHours(rand(9, 22))->addMinutes(rand(0, 59)),
                'updated_at' => now(),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        // Create a few orders from last week
        $lastWeek = Carbon::now()->subWeek();
        
        for ($i = 0; $i < rand(2, 5); $i++) {
            $user = $users->random();
            $orderItems = [];
            $totalPrice = 0;

            $itemCount = rand(1, 2);
            $selectedProducts = $products->random(min($itemCount, $products->count()));

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2);
                $price = $product->price;
                $subtotal = $quantity * $price;
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'created_at' => $lastWeek->copy()->addDays(rand(0, 6))->addHours(rand(9, 22)),
                'updated_at' => now(),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }
    }
}
