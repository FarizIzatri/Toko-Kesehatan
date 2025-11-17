<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <a href="{{ route('cart.index') }}" class="text-sm text-th-muted hover:text-th-foreground transition-colors mb-4 inline-block">
            ← Kembali ke Keranjang
        </a>

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Konfirmasi Pesanan</h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300"><strong>Error:</strong> {{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" class="space-y-6">
            @csrf
            
            <div class="border border-th-border rounded-lg">
                <div class="p-5 sm:p-6">
                    <h3 class="text-lg font-semibold text-th-foreground mb-5">1. Data Pengiriman & Pembayaran</h3>
                    
                    @if (!$codAvailable && $shopCity)
                        <div class="mb-4 rounded-lg bg-yellow-500/10 p-4 text-sm text-yellow-300">
                            <strong>Perhatian:</strong> COD tidak tersedia karena produk berasal dari toko dengan kota berbeda.
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-th-muted mb-1">Alamat Pengiriman:</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">{{ old('shipping_address') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-th-muted mb-1">Metode Pembayaran:</label>
                            <select name="payment_method" id="payment_method" required class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                                <option value="">-- Pilih Metode --</option>
                                <option value="debit_credit">Debit/Credit Card (Prepaid)</option>
                                <option value="bank_transfer">Bank Transfer (Prepaid)</option>
                                @if($codAvailable)
                                    <option value="cod">Bayar di Tempat / COD (Postpaid) - {{ $shopCity }}</option>
                                @else
                                    <option value="cod" disabled>Bayar di Tempat / COD (Tidak tersedia)</option>
                                @endif
                            </select>
                        </div>
                        
                        <div id="cityInput" style="display: none;">
                            <label for="user_city" class="block text-sm font-medium text-th-muted mb-1">Kota Anda (untuk COD):</label>
                            <input type="text" name="user_city" id="user_city" placeholder="Masukkan kota Anda" class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                            @if($codAvailable && $shopCity)
                                <small class="block text-xs text-th-muted mt-1.5">
                                    COD hanya tersedia untuk pengiriman di kota: <strong>{{ $shopCity }}</strong>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div> 
        </form> <div class="border border-th-border rounded-lg mt-6"> 
            <div class="p-5 sm:p-6">
                <h3 class="text-lg font-semibold text-th-foreground mb-5">2. Ringkasan Pesanan</h3>

                <ul role="list" class="-my-4 divide-y divide-th-border">
                    @foreach ($cartItems as $item)
                        <li class="flex items-center py-4">
                            <div class="flex-1">
                                <h4 class="font-medium text-th-foreground">{{ $item->product->name }}</h4>
                                <p class="text-sm text-th-muted">{{ $item->quantity }}x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-th-foreground">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_to" value="checkout">
                                    <button type="submit" class="text-xs font-medium text-red-400 hover:text-red-300 transition-colors">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="flex justify-between text-base font-medium text-th-foreground mt-6 pt-4 border-t border-th-border">
                    <p>Total:</p>
                    <p>Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                </div>
            </div>
        </div> 
        
        <div class="mt-6"> 
            <button type="submit" form="checkoutForm" class="w-full bg-th-foreground text-th-background font-bold py-3 px-6 rounded-lg hover:opacity-80 transition-opacity">
                Buat Pesanan Sekarang
            </button>
        </div>


    </div> @push('scripts')
    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const cityInput = document.getElementById('cityInput');
            const userCityInput = document.getElementById('user_city');
            
            if (this.value === 'cod') {
                cityInput.style.display = 'block';
                userCityInput.required = true;
            } else {
                cityInput.style.display = 'none';
                userCityInput.required = false;
                userCityInput.value = '';
            }
        });
    </script>
    @endpush

</x-app-layout>