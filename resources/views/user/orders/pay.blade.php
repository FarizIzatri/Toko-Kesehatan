<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6 text-center">Selesaikan Pembayaran</h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 rounded-lg bg-blue-500/10 p-4 text-sm text-blue-300 text-center">
            Pesanan Anda <strong>#{{ $order->id }}</strong> telah dibuat. Mohon selesaikan pembayaran.
        </div>

        <div class="border border-th-border rounded-lg p-6 sm:p-8 text-center">
            
            <p class="text-sm text-th-muted">Total Pembayaran:</p>
            <h2 class="text-4xl font-bold text-th-foreground mt-1 mb-2">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </h2>
            <p class="mb-6">
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-500/10 text-yellow-300">
                    MENUNGGU PEMBAYARAN
                </span>
            </p>

            <div class="border-t border-th-border pt-6">
                
                @if($order->payment_method == 'bank_transfer')
                    <h3 class="text-lg font-semibold text-th-foreground">Bank Transfer (Virtual Account)</h3>
                    <p class="text-th-muted mt-2">Silakan transfer ke nomor Virtual Account berikut:</p>
                    <p class="text-2xl sm:text-3xl font-bold text-th-foreground my-3 p-3 bg-th-border/20 rounded-lg tracking-wider">
                        BCA: 123 456 7890123
                    </p>
                    <p class="text-xs text-th-muted">(Ini adalah halaman pembayaran palsu untuk simulasi)</p>
                
                @elseif($order->payment_method == 'debit_credit')
                    <h3 class="text-lg font-semibold text-th-foreground">Debit/Credit Card (via QRIS)</h3>
                    <p class="text-th-muted mt-2">Silakan pindai kode QRIS di bawah ini:</p>
                    <div class="bg-white rounded-lg p-4 inline-block my-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://tokokesehatan.com/pay/{{$order->id}}" alt="Kode QRIS Palsu" class="w-48 h-48">
                    </div>
                    <p class="text-xs text-th-muted">(Ini adalah halaman pembayaran palsu untuk simulasi)</p>
                @endif

            </div>
        </div> <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <form action="{{ route('orders.processPayment', $order->id) }}" method="POST" class="w-full m-0">
                @csrf
                <button type="submit" class="w-full bg-th-foreground text-th-background font-bold rounded-lg px-5 py-3 text-sm hover:opacity-80 transition-opacity">
                    Saya Sudah Bayar
                </button>
            </form>

            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                @csrf
                <button type="submit" class="w-full bg-th-border/50 text-th-muted font-medium rounded-lg px-5 py-3 text-sm hover:bg-th-border/70 transition-colors">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
        
        <div class="text-center mt-6">
             <a href="{{ route('orders.index') }}" class="text-sm text-th-muted hover:text-th-foreground transition-colors">
                Kembali ke Riwayat Pesanan
            </a>
        </div>

    </div>
</x-app-layout>