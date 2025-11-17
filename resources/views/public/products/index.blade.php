<x-app-layout>
    {{-- Filter Kategori (Mobile - Sticky di bawah top bar) --}}
    {{-- [DIUBAH] top-[73px] menjadi top-[4rem] (64px) --}}
    <div class="md:hidden sticky top-[4rem] z-40 bg-th-background border-b border-th-border">
        <button id="category-filter-toggle" class="w-full flex items-center justify-between px-4 py-3 text-th-foreground">
            <span class="font-medium">Filter Kategori</span>
            <svg id="category-filter-icon" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="category-filter-content" class="hidden border-t border-th-border max-h-64 overflow-y-auto">
            <div class="p-4 space-y-2">
                <a href="{{ route('products.index') }}" 
                   class="block w-full text-center {{ !isset($selectedCategory) || !$selectedCategory ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-muted' }} font-medium rounded-lg py-2 px-4 transition-colors">
                    Lihat Semua
                </a>
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="block w-full text-left {{ (isset($selectedCategory) && $selectedCategory == $category->id) ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-foreground' }} font-medium rounded-lg py-2 px-4 transition-colors">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @else
                    <p class="text-sm text-th-muted text-center py-2">Belum ada kategori</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Summary (Mobile - Sticky di atas taskbar) --}}
    @if(auth()->check() && auth()->user()->role == 'user')
        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-th-background border-t border-th-border shadow-lg">
            <button id="order-summary-toggle" class="w-full flex items-center justify-between px-4 py-3 text-th-foreground border-b border-th-border">
                <div class="flex items-center gap-2">
                    <span class="font-bold">Order Summary</span>
                    <span class="text-sm text-th-muted">({{ $cartCount }} Items)</span>
                </div>
                <svg id="order-summary-icon" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="order-summary-content" class="hidden border-t border-th-border max-h-64 overflow-y-auto bg-th-background">
                <div class="p-4">
                    @if($cartItems->isEmpty())
                        <div class="text-center text-th-muted py-4">
                            <p>Keranjang Anda kosong</p>
                        </div>
                    @else
                        <div class="flow-root">
                            <ul role="list" class="-my-4 divide-y divide-th-border">
                                @foreach($cartItems as $item)
                                    <li class="flex py-4">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-th-foreground text-sm">{{ $item->product->name }}</h3>
                                            <p class="text-xs text-th-muted">
                                                {{ $item->quantity }}x Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-th-foreground text-sm">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-4 pt-4 border-t border-th-border">
                            <div class="flex justify-between text-base font-medium text-th-foreground mb-4">
                                <p>Total:</p>
                                <p>Rp {{ number_format($cartTotal, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('cart.index') }}" 
                               class="block w-full text-center bg-th-foreground text-th-background font-bold py-2 px-4 rounded-lg hover:opacity-80 transition-opacity text-sm">
                               LIHAT KERANJANG
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12 pb-28 md:pb-12">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-2 order-2 md:order-1">
                
                <h1 class="text-2xl font-semibold text-th-foreground mb-6">Katalog Produk</h1>

                @if ($products->isEmpty())
                    <p class="text-th-muted">Belum ada produk yang dijual saat ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($products as $product)
                            <div class="border-b border-th-border py-4 last:border-b-0">
                                <div class="flex gap-4">
                                    <a href="{{ route('products.show', $product->id) }}">
                                        @if($product->image)
                                            <img src="{{ asset('storage/'."/" . $product->image) }}" alt="{{ $product->name }}" 
                                                 class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg bg-th-border">
                                        @else
                                            <img src="https://via.placeholder.com/300x180?text=No+Image" alt="No Image" 
                                                 class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg bg-th-border">
                                        @endif
                                    </a>
                                    
                                    <div class="flex-1">
                                        <h3>
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="text-lg font-semibold text-th-foreground hover:underline">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-th-muted">Toko: {{ $product->shop->name }}</p>
                                        <p class="text-sm text-th-muted">Kategori: {{ $product->category->name }}</p>
                                        
                                        <p class="text-base font-medium text-th-foreground mt-1">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="text-sm border border-th-border rounded-lg px-3 py-1 text-th-foreground hover:bg-th-border/20 transition-colors">
                                               Lihat Detail
                                            </a>
                                            
                                            @if(auth()->check() && auth()->user()->role == 'user')
                                                <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form m-0" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <button type="submit" 
                                                            class="text-sm bg-th-foreground text-th-background font-medium rounded-lg px-3 py-1 hover:opacity-80 transition-opacity">
                                                        + Keranjang
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div> 

            <div class="hidden md:block md:col-span-1 order-1 md:order-2">
                <div class="sticky top-12 space-y-6">
                    @if(auth()->check() && auth()->user()->role == 'user')
                        <div class="border border-th-border rounded-lg overflow-hidden">
                            <div class="p-4 border-b border-th-border flex justify-between items-center">
                                <span class="font-bold">Order Summary</span>
                                <span class="text-sm text-th-muted">({{ $cartCount }} Items)</span>
                            </div>

                            <div class="p-4">
                                @if($cartItems->isEmpty())
                                    <div class="text-center text-th-muted py-4">
                                        <p>Keranjang Anda kosong</p>
                                    </div>
                                @else
                                    <div class="flow-root">
                                        <ul role="list" class="-my-4 divide-y divide-th-border">
                                            @foreach($cartItems as $item)
                                                <li class="flex py-4">
                                                    <div class="flex-1">
                                                        <h3 class="font-medium text-th-foreground">{{ $item->product->name }}</h3>
                                                        <p class="text-sm text-th-muted">
                                                            {{ $item->quantity }}x Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-medium text-th-foreground">
                                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            @if($cartItems->isNotEmpty())
                                <div class="p-4 border-t border-th-border bg-th-border/10">
                                    <div class="flex justify-between text-base font-medium text-th-foreground mb-4">
                                        <p>Total Cost:</p>
                                        <p>Rp {{ number_format($cartTotal, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('cart.index') }}" 
                                       class="block w-full text-center bg-th-foreground text-th-background font-bold py-2 px-4 rounded-lg hover:opacity-80 transition-opacity">
                                       LIHAT KERANJANG
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="border border-th-border rounded-lg overflow-hidden">
                        <div class="p-4 border-b border-th-border">
                            <span class="font-bold text-th-foreground">Filter Kategori</span>
                        </div>
                        <div class="p-4">
                            <a href="{{ route('products.index') }}" 
                               class="block w-full text-center mb-3 {{ !isset($selectedCategory) || !$selectedCategory ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-muted hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                Lihat Semua
                            </a>
                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                                           class="block w-full text-left {{ (isset($selectedCategory) && $selectedCategory == $category->id) ? 'bg-th-foreground text-th-background' : 'bg-th-border/50 text-th-foreground hover:bg-th-border/70' }} font-medium rounded-lg py-2 px-4 transition-colors">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach 
                                @else
                                    <p class="text-sm text-th-muted text-center py-2">Belum ada kategori</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div>

    @push('scripts')
        @if(auth()->check() && auth()->user()->role == 'user')
        <script>
            // Script AJAX untuk add to cart
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);

                    fetch('{{ route("cart.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload(); 
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        window.location.reload();
                    });
                });
            });
        </script>
        @endif

        <script>
            // Toggle Filter Kategori (Mobile)
            const categoryToggle = document.getElementById('category-filter-toggle');
            const categoryContent = document.getElementById('category-filter-content');
            const categoryIcon = document.getElementById('category-filter-icon');
            
            if (categoryToggle) {
                categoryToggle.addEventListener('click', function() {
                    categoryContent.classList.toggle('hidden');
                    categoryIcon.classList.toggle('rotate-180');
                });
            }

            // Toggle Order Summary (Mobile)
            const orderSummaryToggle = document.getElementById('order-summary-toggle');
            const orderSummaryContent = document.getElementById('order-summary-content');
            const orderSummaryIcon = document.getElementById('order-summary-icon');
            
            if (orderSummaryToggle) {
                orderSummaryToggle.addEventListener('click', function() {
                    orderSummaryContent.classList.toggle('hidden');
                    orderSummaryIcon.classList.toggle('rotate-180');
                });
            }
        </script>
    @endpush

</x-app-layout>