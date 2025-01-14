<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .receipt-header {
            text-align: center;
        }
        .product-info {
            margin-top: 20px;
        }
        .product-info img {
            width: 100px;
            height: auto;
        }
        .product-info table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        .product-info th, .product-info td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <h1>Receipt</h1>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
    </div>

    <div class="product-info">
        <h3>Product Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="{{ public_path('storage/'.$product->image) }}" alt="Product Image"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total">
        <p><strong>Total: </strong>Rp {{ number_format($order->total_price, 2) }}</p>
    </div>
</body>
</html>
