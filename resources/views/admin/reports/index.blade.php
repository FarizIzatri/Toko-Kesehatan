<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Laporan Transaksi Platform</h1>
        
        <div class="border border-th-border rounded-lg p-5 sm:p-6 mb-6">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="shop_id" class="block text-sm font-medium text-th-muted mb-1">Filter per Toko:</label>
                    <select name="shop_id" 
                            id="shop_id"
                            class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                        <option value="">-- Semua Toko --</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" 
                        class="bg-th-foreground text-th-background font-medium rounded-lg px-4 py-2.5 hover:opacity-80 transition-opacity">
                    Filter
                </button>
            </form>
        </div>

        <div class="border border-th-border rounded-lg p-5 sm:p-6 mb-6 bg-th-border/10">
            <h2 class="text-lg font-semibold text-th-foreground mb-4">Ringkasan Total (Sesuai Filter)</h2>
            <div class="space-y-2">
                <p class="text-th-foreground">
                    <strong class="text-th-muted">Total Pendapatan Platform:</strong> 
                    <span class="text-lg font-bold text-th-foreground">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </p>
                <p class="text-th-foreground">
                    <strong class="text-th-muted">Total Transaksi (Item):</strong> 
                    <span class="text-lg font-bold text-th-foreground">{{ $totalTransactions }}</span>
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-th-border border border-th-border rounded-lg">
                <thead class="bg-th-border/20">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Toko</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">ID Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Jml</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-th-background divide-y divide-th-border">
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $tx->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-4 text-sm text-th-foreground">{{ $tx->shop->name ?? 'Toko Dihapus' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">#{{ $tx->order_id }}</td>
                            <td class="px-4 py-4 text-sm text-th-foreground">{{ $tx->product_name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $tx->quantity }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-th-foreground">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-th-muted">
                                Belum ada transaksi yang selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>