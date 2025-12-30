<?php

namespace App\Http\Controllers;

use App\Jobs\SendLowStockNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Process checkout: Move cart items to order.
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        // Get user's cart
        $cart = Cart::where('user_id', $user->id)
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        // Validate stock availability before processing
        foreach ($cart->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return back()->withErrors([
                    'stock' => 'Insufficient stock for ' . $item->product->name . '. Available: ' . $item->product->stock_quantity,
                ]);
            }
        }

        // Calculate total price
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Use database transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
            ]);

            // Create order items and decrement stock
            foreach ($cart->items as $cartItem) {
                // Create order item with price snapshot
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price, // Snapshot price at time of purchase
                ]);

                // Decrement product stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
                
                // Refresh the product to get updated stock_quantity
                $cartItem->product->refresh();
                
                // Check if stock is running low (< 5) and dispatch notification job
                if ($cartItem->product->stock_quantity < 5) {
                    SendLowStockNotification::dispatch($cartItem->product);
                }
            }

            // Empty the cart (delete all cart items)
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'An error occurred during checkout. Please try again.',
            ]);
        }
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('items.product');

        return Inertia::render('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Display user's order history.
     */
    public function index()
    {
        $user = auth()->user();
        
        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'item_count' => $order->items->sum('quantity'),
                ];
            });

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }
}
