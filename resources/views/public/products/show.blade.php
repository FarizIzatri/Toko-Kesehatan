<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <a href="{{ route('products.index') }}" class="text-sm text-th-muted hover:text-th-foreground transition-colors mb-4 inline-block">
            ← Kembali ke Katalog
        </a>

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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-th-border">
            
            <div class="md:col-span-1">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg bg-th-border">
                @else
                    <img src="https://via.placeholder.com/400x400?text=No+Image" alt="No Image" class="w-full h-auto object-cover rounded-lg bg-th-border">
                @endif
            </div>
            
            <div class="md:col-span-2 flex flex-col">
                <h1 class="text-2xl font-semibold text-th-foreground mb-2">{{ $product->name }}</h1>
                
                <div class="text-3xl font-bold text-th-foreground my-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <div class="my-3 space-y-1 text-sm text-th-muted">
                    <p><strong>Toko:</strong> {{ $product->shop->name }}</p>
                    <p><strong>Kategori:</strong> {{ $product->category->name ?? 'Tidak ada kategori' }}</p>
                    <p><strong>Stok:</strong> 
                        @if($product->stock > 0)
                            <span class="text-green-400">{{ $product->stock }} unit tersedia</span>
                        @else
                            <span class="text-red-400">Stok habis</span>
                        @endif
                    </p>
                    @if($totalFeedbacks > 0)
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))★@else☆@endif
                                @endfor
                            </span>
                            <span>{{ number_format($averageRating, 1) }} ({{ $totalFeedbacks }} ulasan)</span>
                        </div>
                    @else
                        <p><em>Belum ada ulasan</em></p>
                    @endif
                </div>

                <div class="mt-auto pt-4">
                    @if(auth()->check() && auth()->user()->role == 'user')
                        <form action="{{ route('cart.store') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" 
                                    class="w-full bg-th-foreground text-th-background font-medium rounded-lg px-4 py-2.5 hover:opacity-80 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed" 
                                    {{ $product->stock == 0 ? 'disabled' : '' }}>
                                {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                            </button>
                        </form>
                    @elseif(!auth()->check())
                        <p class="text-sm text-th-muted">Silakan <a href="{{ route('login') }}" class="text-th-foreground hover:underline font-medium">login</a> untuk membeli.</p>
                    @endif
                </div>
            </div>
        </div>
        @if($product->description)
            <div class="py-6 border-b border-th-border">
                <h3 class="text-lg font-semibold text-th-foreground mb-3">Deskripsi Produk</h3>
                <p class="text-th-foreground/90 whitespace-pre-wrap">{{ $product->description }}</p>
            </div>
        @endif

        <div class="py-6 border-b border-th-border">
            <h2 class="text-lg font-semibold text-th-foreground mb-4">Ulasan Pembeli ({{ $totalFeedbacks }})</h2>
            
            @if($product->feedbacks->count() > 0)
                <div class="space-y-5">
                    @foreach($product->feedbacks as $feedback)
                        <div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-th-foreground">{{ $feedback->user->name }}</span>
                                <span class="text-sm text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)★@else☆@endif
                                    @endfor
                                    ({{ $feedback->rating }}/5)
                                </span>
                            </div>
                            <p class="text-th-foreground/90 my-2">{{ $feedback->comment }}</p>
                            <small class="text-xs text-th-muted">{{ $feedback->created_at->format('d M Y') }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-th-muted"><em>Belum ada ulasan untuk produk ini.</em></p>
            @endif
        </div>

        <div class="py-6 border-b border-th-border">
            @if($hasPurchased)
                @if($hasReviewed)
                    <div class="border border-th-border rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-th-foreground mb-2">Ulasan Anda</h3>
                        <p class="text-sm text-th-muted">Anda sudah pernah memberi ulasan untuk produk ini. Terima kasih!</p>
                    </div>
                @else
                    <div class="border border-th-border rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-th-foreground mb-4">Tulis Ulasan Anda</h3>
                        
                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-500/10 p-4 text-sm text-red-300">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('feedback.store', $product->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="block text-sm font-medium text-th-muted mb-1">Rating Anda</label>
                                <select name="rating" id="rating" class="w-full bg-th-border/50 border-th-border rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground" required>
                                    <option value="">Pilih rating</option>
                                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 ★★★★★</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 ★★★★☆</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 ★★★☆☆</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 ★★☆☆☆</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 ★☆☆☆☆</option>
                                </select>
                                @error('rating') <div class="text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label for="comment" class="block text-sm font-medium text-th-muted mb-1">Ulasan Anda</label>
                                <textarea name="comment" id="comment" required rows="4" class="w-full bg-th-border/50 border-th-border rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">{{ old('comment') }}</textarea>
                                @error('comment') <div class="text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="bg-th-foreground text-th-background font-medium rounded-lg px-4 py-2 text-sm hover:opacity-80 transition-opacity">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @endif
            @elseif(auth()->check() && auth()->user()->role == 'user')
                 <div class="border border-th-border rounded-lg p-4">
                    <p class="text-sm text-th-muted">Anda harus membeli dan menerima produk ini (status order 'completed') sebelum dapat memberi ulasan.</p>
                </div>
            @endif
        </div>

        <div class="py-6">
            <h2 class="text-lg font-semibold text-th-foreground mb-4">Pertanyaan ({{ $product->productQuestions->count() }})</h2>

            @auth
                <div class="border border-th-border rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-th-foreground mb-4">Punya pertanyaan?</h3>
                    <form action="{{ route('questions.store', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="question_comment" class="block text-sm font-medium text-th-muted mb-1">Tulis pertanyaan Anda di sini:</label>
                            <textarea name="comment" id="question_comment" required rows="4" class="w-full bg-th-border/50 border-th-border rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground">{{ old('comment') }}</textarea>
                            @error('comment') <div class="text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="bg-th-foreground text-th-background font-medium rounded-lg px-4 py-2 text-sm hover:opacity-80 transition-opacity">
                            Kirim Pertanyaan
                        </button>
                    </form>
                </div>
            @else
                <p class="text-sm text-th-muted mb-6"><em><a href="{{ route('login') }}" class="text-th-foreground hover:underline font-medium">Login</a> untuk bertanya kepada penjual.</em></p>
            @endif


            @if($product->productQuestions->count() > 0)
                <div class="space-y-6">
                    @foreach($product->productQuestions as $question)
                        <div>
                            <div class="font-semibold text-th-foreground">{{ $question->user->name }} bertanya:</div>
                            <p class="text-th-foreground/90 my-2">{{ $question->comment }}</p>
                            <small class="text-xs text-th-muted">{{ $question->created_at->format('d M Y') }}</small>

                            @if($question->reply)
                                <div class="mt-3 ml-4 p-3 border-l-2 border-th-border bg-th-border/20 rounded-r-lg">
                                    <p class="text-sm font-semibold text-th-foreground">{{ $product->shop->name }} menjawab:</p>
                                    <p class="text-th-foreground/90 my-1">{{ $question->reply }}</p>
                                    <small class="text-xs text-th-muted">{{ $question->replied_at->format('d M Y') }}</small>
                                </div>
                            
                            @elseif(auth()->check() && auth()->user()->role == 'vendor' && auth()->user()->shop->id == $product->shop_id)
                                <div class="mt-4 ml-4">
                                    <form action="{{ route('vendor.questions.reply', $question->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="reply_{{ $question->id }}" class="block text-sm font-medium text-th-muted mb-1">Balas pertanyaan ini:</label>
                                            <textarea name="reply" id="reply_{{ $question->id }}" rows="3" required class="w-full bg-th-border/50 border-th-border rounded-lg p-2 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground"></textarea>
                                        </div>
                                        <button type="submit" class="bg-th-foreground text-th-background font-medium rounded-lg px-3 py-1 text-xs hover:opacity-80 transition-opacity">
                                            Kirim Balasan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-th-muted"><em>Belum ada pertanyaan untuk produk ini.</em></p>
            @endif
        </div>

    </div> </x-app-layout>