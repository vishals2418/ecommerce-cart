<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a5568;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 14px;
            color: #718096;
            text-transform: uppercase;
        }
        .section {
            margin: 30px 0;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f7fafc;
            font-weight: bold;
            color: #2d3748;
        }
        tr:hover {
            background-color: #f7fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #718096;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Daily Sales Report</h1>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">{{ $salesData['date'] }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $salesData['total_orders'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">${{ number_format($salesData['total_revenue'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Items Sold</div>
            <div class="stat-value">{{ $salesData['total_items_sold'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Average Order</div>
            <div class="stat-value">
                ${{ $salesData['total_orders'] > 0 ? number_format($salesData['total_revenue'] / $salesData['total_orders'], 2) : '0.00' }}
            </div>
        </div>
    </div>

    @if($salesData['total_orders'] > 0)
        <div class="section">
            <div class="section-title">📦 Today's Orders</div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Time</th>
                        <th class="text-center">Items</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['orders'] as $order)
                    <tr>
                        <td>#{{ $order['id'] }}</td>
                        <td>{{ $order['created_at'] }}</td>
                        <td class="text-center">{{ $order['item_count'] }}</td>
                        <td class="text-right">${{ number_format($order['total_price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($salesData['top_products']) > 0)
        <div class="section">
            <div class="section-title">🏆 Top Selling Products</div>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th class="text-center">Quantity Sold</th>
                        <th class="text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['top_products'] as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td class="text-center">{{ $product['quantity'] }}</td>
                        <td class="text-right">${{ number_format($product['revenue'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @else
        <div class="no-data">
            <p>No orders were placed today.</p>
        </div>
    @endif

    <div class="footer">
        <p>This is an automated daily sales report from your e-commerce system.</p>
        <p>Generated at: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>

