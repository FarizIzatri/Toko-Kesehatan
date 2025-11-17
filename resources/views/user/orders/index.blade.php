<x-app-layout>
    {{-- Filter Status (Mobile - Sticky di bawah top bar) --}}
    <div class="md:hidden sticky top-[73px] z-40 bg-th-background border-b border-th-border">
        <button id="status-filter-toggle" class="w-full flex items-center justify-between px-4 py-3 text-th-foreground">
            <span class="font-medium">Filter Status</span>
            <svg id="status-filter-icon" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="status-filter-content" class="hidden border-t border-th-border max-h-64 overflow-y-auto">
            <div class="p-4 space-y-2">
                <a href="{{ route('orders.index') }}" 
                   class="block w-full text-center {{ !isset($selectedStatus) || !$selectedStatus ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-muted' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Lihat Semua
                </a>
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'pending') ? 'bg-yellow-500/10 text-yellow-300' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Menunggu Pembayaran
                </a>
                <a href="{{ route('orders.index', ['status' => 'paid']) }}" 
                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'paid') ? 'bg-blue-500/10 text-blue-300' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Terbayar
                </a>
                <a href="{{ route('orders.index', ['status' => 'shipped']) }}" 
                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'shipped') ? 'bg-cyan-500/10 text-cyan-300' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Dikirim
                </a>
                <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'completed') ? 'bg-green-500/10 text-green-300' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Selesai
                </a>
                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'cancelled') ? 'bg-red-500/10 text-red-300' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Dibatalkan
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12 pb-20 md:pb-12">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2 order-2 md:order-1">
                <h1 class="text-2xl font-semibold text-th-foreground mb-6">Riwayat Pesanan Saya</h1>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">{{ session('error') }}</div>
                @endif

                @if ($orders->isEmpty())
                    <div class="border border-th-border rounded-lg p-6 text-center">
                        <p class="text-th-muted mb-4">Anda belum memiliki pesanan.</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-block bg-th-foreground text-th-background font-bold py-2 px-5 rounded-lg hover:opacity-80 transition-opacity">
                            Mulai Belanja
                        </a>
                    </div>
                @else
            @php
                $statusClasses = [
                    'pending' => 'bg-yellow-500/10 text-yellow-300',
                    'paid' => 'bg-blue-500/10 text-blue-300',
                    'shipped' => 'bg-cyan-500/10 text-cyan-300',
                    'completed' => 'bg-green-500/10 text-green-300',
                    'cancelled' => 'bg-red-500/10 text-red-300',
                ];
                $statusText = [
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Terbayar',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];
                @endphp

                <div class="divide-y divide-th-border">
                    @foreach ($orders as $order)
                    <div class="py-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-lg font-semibold text-th-foreground">Pesanan #{{ $order->id }}</span>
                                <p class="text-sm text-th-muted">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusClasses[$order->status] ?? 'bg-th-border text-th-muted' }}">
                                    {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1 text-sm text-th-muted mb-4">
                            <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                            <p><strong>Pembayaran:</strong> 
                                @if($order->payment_method == 'debit_credit') Debit/Credit Card
                                @elseif($order->payment_method == 'cod') Cash on Delivery (COD)
                                @elseif($order->payment_method == 'bank_transfer') Bank Transfer
                                @else {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                @endif
                            </p>
                        </div>

                        <div class="space-y-3 rounded-lg bg-th-border/20 p-4 mb-4">
                            <h4 class="text-base font-medium text-th-foreground mb-2">Item Pesanan:</h4>
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between text-sm">
                                    <div class="text-th-foreground/90">
                                        {{ $item->product_name }}
                                        <span class="text-th-muted block">({{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                    </div>
                                    <div class="font-medium text-th-foreground/90">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end text-lg font-bold text-th-foreground mb-5">
                            Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>

                        <div class="flex items-center gap-4">
                            @if($order->status == 'pending')
                                <a href="{{ route('orders.pay', $order->id) }}" 
                                   class="bg-th-foreground text-th-background font-medium rounded-lg px-4 py-2 text-sm hover:opacity-80 transition-opacity">
                                    Bayar Sekarang
                                </a>
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-red-500/10 text-red-400 font-medium rounded-lg px-4 py-2 text-sm hover:bg-red-500/20 transition-colors">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            
                            @elseif($order->status == 'paid')
                                <span class="text-sm text-blue-300">Menunggu konfirmasi dan pengiriman oleh vendor.</span>
                            
                            @elseif($order->status == 'shipped')
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin pesanan sudah sampai? Status akan berubah menjadi Selesai.');">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-500/10 text-green-300 font-medium rounded-lg px-4 py-2 text-sm hover:bg-green-500/20 transition-colors">
                                            Selesai
                                        </button>
                                    </form>
                                    <form action="{{ route('orders.resendInvoice', $order->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-blue-500/10 text-blue-300 font-medium rounded-lg px-4 py-2 text-sm hover:bg-blue-500/20 transition-colors">
                                            Kirim Invoice Lagi
                                        </button>
                                    </form>
                                </div>
                            
                            @elseif($order->status == 'completed')
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-green-300">✓ Pesanan Selesai</span>
                                    <form action="{{ route('orders.resendInvoice', $order->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-blue-500/10 text-blue-300 font-medium rounded-lg px-4 py-2 text-sm hover:bg-blue-500/20 transition-colors">
                                            Kirim Invoice Lagi
                                        </button>
                                    </form>
                                </div>
                                
                            @elseif($order->status == 'cancelled')
                                <span class="text-sm text-red-300">Pesanan Dibatalkan</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="md:col-span-1 order-1 md:order-2">
                <div class="sticky top-12">
                    {{-- Filter Status --}}
                    <div class="border border-th-border rounded-lg overflow-hidden">
                        <div class="p-4 border-b border-th-border">
                            <span class="font-bold text-th-foreground">Filter Status</span>
                        </div>
                        <div class="p-4">
                            <a href="{{ route('orders.index') }}" 
                               class="block w-full text-center mb-3 {{ !isset($selectedStatus) || !$selectedStatus ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-muted hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                Lihat Semua
                            </a>
                            <div class="space-y-2">
                                <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'pending') ? 'bg-yellow-500/10 text-yellow-300' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                    Menunggu Pembayaran
                                </a>
                                <a href="{{ route('orders.index', ['status' => 'paid']) }}" 
                                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'paid') ? 'bg-blue-500/10 text-blue-300' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                    Terbayar
                                </a>
                                <a href="{{ route('orders.index', ['status' => 'shipped']) }}" 
                                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'shipped') ? 'bg-cyan-500/10 text-cyan-300' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                    Dikirim
                                </a>
                                <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
                                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'completed') ? 'bg-green-500/10 text-green-300' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                    Selesai
                                </a>
                                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
                                   class="block w-full text-left {{ (isset($selectedStatus) && $selectedStatus == 'cancelled') ? 'bg-red-500/10 text-red-300' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                    Dibatalkan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // Toggle Filter Status (Mobile)
            const statusToggle = document.getElementById('status-filter-toggle');
            const statusContent = document.getElementById('status-filter-content');
            const statusIcon = document.getElementById('status-filter-icon');
            
            if (statusToggle) {
                statusToggle.addEventListener('click', function() {
                    statusContent.classList.toggle('hidden');
                    statusIcon.classList.toggle('rotate-180');
                });
            }
        </script>
    @endpush
</x-app-layout>