<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .product-info {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .stock-quantity {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Low Stock Alert</h1>
    
    <div class="alert-box">
        <strong>⚠️ Warning:</strong> A product is running low on stock and needs to be restocked.
    </div>

    <div class="product-info">
        <div class="product-name">{{ $product->name }}</div>
        <p><strong>Current Stock:</strong> <span class="stock-quantity">{{ $product->stock_quantity }}</span> units</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
        <p><strong>Product ID:</strong> #{{ $product->id }}</p>
    </div>

    <p>Please consider restocking this product to avoid running out of inventory.</p>

    <div class="footer">
        <p>This is an automated notification from your e-commerce system.</p>
        <p>Generated at: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>

