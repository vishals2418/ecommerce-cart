<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating daily sales report...');

        // Get today's date
        $today = now()->format('Y-m-d');

        // Query all orders created today
        $orders = Order::whereDate('created_at', $today)
            ->with('items.product')
            ->get();

        // Calculate sales statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_price');
        $totalItemsSold = $orders->sum(function ($order) {
            return $order->items->sum('quantity');
        });

        // Get top selling products
        $productSales = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $productId = $item->product_id;
                $productName = $item->product->name;
                
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'name' => $productName,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                
                $productSales[$productId]['quantity'] += $item->quantity;
                $productSales[$productId]['revenue'] += $item->subtotal;
            }
        }

        // Sort by revenue (descending)
        usort($productSales, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        // Prepare sales data
        $salesData = [
            'date' => $today,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_items_sold' => $totalItemsSold,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at->format('H:i:s'),
                    'item_count' => $order->items->sum('quantity'),
                ];
            })->toArray(),
            'top_products' => array_slice($productSales, 0, 10), // Top 10 products
        ];

        // Send email
        try {
            Mail::to('vishal.s@yopmail.com')->send(new DailySalesReport($salesData));
            $this->info('Daily sales report sent successfully!');
            $this->info("Total Orders: {$totalOrders}");
            $this->info("Total Revenue: $" . number_format($totalRevenue, 2));
            $this->info("Total Items Sold: {$totalItemsSold}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send daily sales report: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
