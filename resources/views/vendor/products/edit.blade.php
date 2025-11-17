<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <a href="{{ route('vendor.products.index') }}" class="text-sm text-th-muted hover:text-th-foreground transition-colors mb-4 inline-block">
            ← Kembali ke Daftar Produk
        </a>

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Edit Produk</h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        <div class="border border-th-border rounded-lg p-5 sm:p-6">
            <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-th-muted mb-1">Nama Produk:</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product->name) }}" 
                           required
                           class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                    @error('name')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-th-muted mb-1">Kategori:</label>
                    <select name="category_id" 
                            id="category_id" 
                            required
                            class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-th-muted mb-1">Harga:</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           value="{{ old('price', $product->price) }}" 
                           required 
                           placeholder="Contoh: 50000"
                           min="0"
                           step="1"
                           class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                    @error('price')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-th-muted mb-1">Stok:</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock', $product->stock) }}" 
                           required
                           min="0"
                           step="1"
                           class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-th-muted mb-1">Deskripsi:</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="image" class="block text-sm font-medium text-th-muted mb-1">Gambar Produk:</label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                    <small class="block text-xs text-th-muted mt-1.5">Kosongkan jika tidak ingin mengubah gambar.</small>
                    @error('image')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                    
                    @if($product->image)
                        <div class="mt-4">
                            <p class="text-sm text-th-muted mb-2">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-40 h-auto rounded-lg border border-th-border">
                        </div>
                    @endif
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-th-foreground text-th-background font-bold py-2.5 px-6 rounded-lg hover:opacity-80 transition-opacity">
                        Update Produk
                    </button>
                    <a href="{{ route('vendor.products.index') }}" 
                       class="bg-th-border/50 text-th-muted font-medium py-2.5 px-6 rounded-lg hover:bg-th-border/70 transition-colors inline-flex items-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>