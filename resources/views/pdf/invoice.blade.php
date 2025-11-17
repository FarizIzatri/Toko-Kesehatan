<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .details, .items { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .details td { padding: 5px; }
        .items th, .items td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items th { background-color: #f4f4f4; }
        .total { text-align: right; font-size: 1.2em; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <table class="details">
            <tr>
                <td><strong>No. Pesanan:</strong> #{{ $order->id }}</td>
                <td><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>Nama:</strong> {{ $order->user->name }}</td>
                <td><strong>Email:</strong> {{ $order->user->email }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Alamat Kirim:</strong> {{ $order->shipping_address }}</td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </div>
    </div>
</body>
</html>