<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-th-foreground">Kelola Produk Saya</h1>
            <a href="{{ route('vendor.products.create') }}" 
               class="inline-block bg-th-foreground text-th-background font-bold py-2 px-5 rounded-lg hover:opacity-80 transition-opacity">
               Tambah Produk Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="border border-th-border rounded-lg p-6 text-center">
                <p class="text-th-muted">Anda belum memiliki produk.</p>
            </div>
        
        @else
            <div class="divide-y divide-th-border border-t border-b border-th-border">
                
                @foreach ($products as $product)
                    <div class="py-4">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-th-foreground">{{ $product->name }}</h3>
                                <p class="text-sm text-th-muted">Kategori: {{ $product->category->name }}</p>
                                
                                <div class="flex gap-4 mt-1">
                                    <p class="text-base font-medium text-th-foreground">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-base text-th-muted">
                                        Stok: {{ $product->stock }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex-shrink-0 flex items-center gap-3">
                                <a href="{{ route('vendor.products.edit', $product->id) }}" 
                                   class="text-sm border border-th-border rounded-lg px-3 py-1 text-th-foreground hover:bg-th-border/20 transition-colors">
                                   Edit
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm text-red-400 hover:text-red-300 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>