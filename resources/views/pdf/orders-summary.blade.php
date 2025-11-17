<!DOCTYPE html>
<html>
<head>
    <title>Rangkuman Pesanan - {{ $month }}</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px;
            color: #333;
        }
        .container { 
            width: 100%; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 { 
            margin: 0; 
            font-size: 24px;
            color: #1a1a1a;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1a1a1a;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            width: 50%;
        }
        .stats-cell strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .stats-value {
            font-size: 14px;
            color: #1a1a1a;
        }
        .revenue-highlight {
            background-color: #e8f5e9;
            font-weight: bold;
            color: #2e7d32;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .comparison {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .positive {
            color: #2e7d32;
        }
        .negative {
            color: #c62828;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RANGKUMAN PESANAN</h1>
            <p>{{ $isAdmin ? 'Semua Pesanan' : 'Toko: ' . $shop->name }}</p>
            <p>Periode: {{ $month }}</p>
            <p>Dibuat pada: {{ now()->format('d M Y H:i') }}</p>
        </div>

        <!-- Statistik Utama -->
        <div class="section">
            <div class="section-title">Statistik Utama</div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>Total Pesanan</strong>
                        <span class="stats-value">{{ $totalOrders }}</span>
                    </div>
                    <div class="stats-cell revenue-highlight">
                        <strong>Total Pendapatan</strong>
                        <span class="stats-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Status Pesanan -->
        <div class="section">
            <div class="section-title">Status Pesanan</div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>Pending</strong>
                        <span class="stats-value">{{ $pendingOrders }}</span>
                    </div>
                    <div class="stats-cell">
                        <strong>Paid</strong>
                        <span class="stats-value">{{ $paidOrders }}</span>
                    </div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>Shipped</strong>
                        <span class="stats-value">{{ $shippedOrders }}</span>
                    </div>
                    <div class="stats-cell">
                        <strong>Completed</strong>
                        <span class="stats-value">{{ $completedOrders }}</span>
                    </div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>Cancelled</strong>
                        <span class="stats-value">{{ $cancelledOrders }}</span>
                    </div>
                    <div class="stats-cell">
                        <strong>Total</strong>
                        <span class="stats-value">{{ $totalOrders }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perbandingan Bulan -->
        @if($prevMonthRevenue > 0 || $totalRevenue > 0)
        <div class="section">
            <div class="section-title">Perbandingan Bulan</div>
            <div class="comparison">
                <p><strong>Bulan Ini ({{ $month }}):</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p><strong>Bulan Lalu ({{ $prevMonthName }}):</strong> Rp {{ number_format($prevMonthRevenue, 0, ',', '.') }}</p>
                @php
                    $difference = $totalRevenue - $prevMonthRevenue;
                    $percentage = $prevMonthRevenue > 0 ? (($difference / $prevMonthRevenue) * 100) : 0;
                @endphp
                <p>
                    <strong>Selisih:</strong> 
                    <span class="{{ $difference >= 0 ? 'positive' : 'negative' }}">
                        {{ $difference >= 0 ? '+' : '' }}Rp {{ number_format(abs($difference), 0, ',', '.') }}
                        ({{ number_format(abs($percentage), 2) }}%)
                    </span>
                </p>
            </div>
        </div>
        @endif

        <!-- Pendapatan Berdasarkan Metode Pembayaran -->
        @if(count($revenueByPayment) > 0)
        <div class="section">
            <div class="section-title">Pendapatan Berdasarkan Metode Pembayaran</div>
            <table>
                <thead>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByPayment as $method => $amount)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                        <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totalRevenue > 0 ? number_format(($amount / $totalRevenue) * 100, 2) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Daftar Pesanan -->
        @if($orders->count() > 0)
        <div class="section">
            <div class="section-title">Daftar Pesanan ({{ $orders->count() }} pesanan)</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pemesan</th>
                        @if(!$isAdmin)
                        <th>Produk</th>
                        @endif
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        if (!$isAdmin) {
                            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
                            $myTotal = $myOrderItems->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        } else {
                            $myTotal = $order->total_amount;
                        }
                    @endphp
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        @if(!$isAdmin)
                        <td>
                            @foreach($order->orderItems->where('shop_id', $shop->id) as $item)
                                {{ $item->product_name }} (x{{ $item->quantity }})<br>
                            @endforeach
                        </td>
                        @endif
                        <td class="text-right">Rp {{ number_format($myTotal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $statusText = [
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'shipped' => 'Shipped',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ];
                            @endphp
                            {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <p>Dokumen ini dibuat secara otomatis oleh sistem {{ config('app.name') }}</p>
            <p>Halaman 1</p>
        </div>
    </div>
</body>
</html>

