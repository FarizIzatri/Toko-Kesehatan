<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-th-foreground">Manajemen Pesanan Toko Saya</h1>
            <a href="{{ route('vendor.orders.downloadSummary') }}" class="inline-flex items-center gap-2 bg-blue-500/10 text-blue-300 font-medium rounded-lg px-4 py-2 text-sm hover:bg-blue-500/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Rangkuman
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="border border-th-border rounded-lg p-6 text-center">
                <p class="text-th-muted">Belum ada pesanan untuk toko Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-th-border border border-th-border rounded-lg">
                    <thead class="bg-th-border/20">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">ID Pesanan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Pemesan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Produk dari Toko Saya</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Total (Produk Saya)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Metode Bayar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Alamat Pengiriman</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-th-background divide-y divide-th-border">
                        @foreach ($orders as $order)
                            @php
                                $shopId = auth()->user()->shop->id ?? null;
                                $myOrderItems = $order->orderItems->where('shop_id', $shopId);
                                $myTotal = $myOrderItems->sum(function($item) {
                                    return $item->price * $item->quantity;
                                });
                                $statusClasses = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-300',
                                    'paid' => 'bg-blue-500/10 text-blue-300',
                                    'shipped' => 'bg-cyan-500/10 text-cyan-300',
                                    'completed' => 'bg-green-500/10 text-green-300',
                                    'cancelled' => 'bg-red-500/10 text-red-300',
                                ];
                            @endphp
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">#{{ $order->id }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm text-th-foreground">
                                    <div class="space-y-1">
                                        @foreach ($myOrderItems as $item)
                                            <div>{{ $item->product_name }} (x{{ $item->quantity }})</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-th-foreground">Rp {{ number_format($myTotal, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-th-muted">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                <td class="px-4 py-4 text-sm text-th-muted">{{ $order->shipping_address }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClasses[$order->status] ?? 'bg-th-border text-th-muted' }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if ($order->status == 'pending')
                                        <span class="text-yellow-300">Menunggu pembayaran</span>
                                    @elseif ($order->status == 'paid')
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin mengirim pesanan ini?');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="shipped">
                                                <button type="submit" class="bg-green-500/10 text-green-300 font-medium rounded-lg px-3 py-1.5 text-xs hover:bg-green-500/20 transition-colors">
                                                    Kirim
                                                </button>
                                            </form>
                                            <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="bg-red-500/10 text-red-400 font-medium rounded-lg px-3 py-1.5 text-xs hover:bg-red-500/20 transition-colors">
                                                    Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    @elseif ($order->status == 'shipped')
                                        <span class="text-cyan-300">✓ Terkirim</span>
                                    @elseif ($order->status == 'completed')
                                        <span class="text-green-300">✓ Selesai</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="text-red-300">✗ Dibatalkan</span>
                                    @else
                                        <span class="text-th-muted">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-app-layout>

