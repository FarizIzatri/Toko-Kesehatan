<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Keranjang Belanja Anda</h1>

        @if ($cartItems->isEmpty())
            <div class="border border-th-border rounded-lg p-6 text-center">
                <p class="text-th-muted mb-4">Keranjang Anda kosong.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-th-foreground text-th-background font-bold py-2 px-5 rounded-lg hover:opacity-80 transition-opacity">
                    Mulai Belanja
                </a>
            </div>
        
        @else
            <div class="flow-root">
                <ul role="list" class="-my-6 divide-y divide-th-border">
                    @foreach ($cartItems as $item)
                        <li class="flex py-6">
                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-th-border">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="h-full w-full object-cover object-center bg-th-border">
                            </div>

                            <div class="ml-4 flex flex-1 flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-th-foreground">
                                        <h3>
                                            <a href="{{ route('products.show', $item->product->id) }}">{{ $item->product->name }}</a>
                                        </h3>
                                        <p class="ml-4">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-th-muted">Rp {{ number_format($item->product->price, 0, ',', '.') }} (satuan)</p>
                                </div>
                                
                                <div class="flex flex-1 items-end justify-between text-sm">
                                    <p class="text-th-muted">Jumlah: {{ $item->quantity }}</p>

                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-400 hover:text-red-300 transition-colors">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="border-t border-th-border pt-6 mt-6">
                <div class="flex justify-between text-lg font-medium text-th-foreground">
                    <p>Total</p>
                    <p>Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                </div>
                
                <div class="mt-6 space-y-3">
                    <a href="{{ route('checkout.create') }}" 
                       class="block w-full text-center bg-th-foreground text-th-background font-bold py-2.5 px-6 rounded-lg hover:opacity-80 transition-opacity">
                        Lanjut ke Pembayaran
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="block w-full text-center text-sm font-medium text-th-foreground hover:text-th-muted transition-colors">
                        atau Lanjutkan Belanja
                    </a>
                </div>
            </div>
        @endif

    </div> </x-app-layout>