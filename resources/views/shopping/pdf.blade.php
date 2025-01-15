<!-- resources/views/shopping/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .cart-item {
            margin-bottom: 15px;
        }
        .cart-item h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        .cart-item .price {
            font-weight: bold;
        }
        .cart-total {
            margin-top: 30px;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Invoice</h1>
    <p>Berikut adalah ringkasan pesanan Anda:</p>
    
    @foreach ($cart as $item)
        <div class="cart-item">
            <h3>{{ $item['name'] }} ({{ $item['quantity'] }})</h3>
            <div class="price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
        </div>
    @endforeach

    <div class="cart-total">
        <p>Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
    </div>
</body>
</html>
