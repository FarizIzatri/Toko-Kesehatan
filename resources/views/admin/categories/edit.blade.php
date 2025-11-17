<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <a href="{{ route('admin.categories.index') }}" class="text-sm text-th-muted hover:text-th-foreground transition-colors mb-4 inline-block">
            ← Kembali ke Daftar Kategori
        </a>

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Edit Kategori: {{ $category->name }}</h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        <div class="border border-th-border rounded-lg p-5 sm:p-6">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-th-muted mb-1">Nama Kategori:</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           required
                           class="w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">
                    @error('name')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-th-foreground text-th-background font-bold py-2.5 px-6 rounded-lg hover:opacity-80 transition-opacity">
                        Update
                    </button>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-th-border/50 text-th-muted font-medium py-2.5 px-6 rounded-lg hover:bg-th-border/70 transition-colors inline-flex items-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>