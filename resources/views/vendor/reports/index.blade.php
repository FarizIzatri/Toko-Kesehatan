<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Laporan Transaksi Toko Anda</h1>
        
        <div class="border border-th-border rounded-lg p-5 sm:p-6 mb-6 bg-th-border/10">
            <h2 class="text-lg font-semibold text-th-foreground mb-4">Ringkasan Total</h2>
            <div class="space-y-2">
                <p class="text-th-foreground">
                    <strong class="text-th-muted">Total Pendapatan:</strong> 
                    <span class="text-lg font-bold text-th-foreground">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </p>
                <p class="text-th-foreground">
                    <strong class="text-th-muted">Total Item Terjual:</strong> 
                    <span class="text-lg font-bold text-th-foreground">{{ $totalItems }}</span>
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-th-border border border-th-border rounded-lg">
                <thead class="bg-th-border/20">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">ID Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Jml</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-th-background divide-y divide-th-border">
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $tx->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">#{{ $tx->order_id }}</td>
                            <td class="px-4 py-4 text-sm text-th-foreground">{{ $tx->product_name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-foreground">{{ $tx->quantity }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-th-muted">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
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