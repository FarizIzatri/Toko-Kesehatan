<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-th-foreground">Daftar Kategori</h1>
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-block bg-th-foreground text-th-background font-bold py-2 px-5 rounded-lg hover:opacity-80 transition-opacity">
               Tambah Kategori Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($categories->isEmpty())
            <div class="border border-th-border rounded-lg p-6 text-center">
                <p class="text-th-muted">Belum ada kategori.</p>
            </div>
        @else
            <div class="divide-y divide-th-border border-t border-b border-th-border">
                @foreach ($categories as $category)
                    <div class="py-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-th-foreground">{{ $category->name }}</h3>
                            </div>
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="text-sm border border-th-border rounded-lg px-3 py-1 text-th-foreground hover:bg-th-border/20 transition-colors">
                                   Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                      method="POST" 
                                      class="m-0" 
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
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