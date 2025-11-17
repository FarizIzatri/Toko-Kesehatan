<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    
    public function indexPublic(Request $request)
    {
        $query = Product::whereHas('shop', function ($query) {
            $query->where('status', 'approved');
        })
        ->with(['shop', 'category']);

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        // Get all categories for filter
        $categories = Category::all();

        $cartItems = collect();
        $cartTotal = 0;
        $cartCount = 0;
        
        if (Auth::check() && Auth::user()->role == 'user') {
            $cartItems = \App\Models\CartItem::where('user_id', Auth::id())
                                             ->with('product')
                                             ->get();
            $cartCount = $cartItems->count();
            foreach ($cartItems as $item) {
                $cartTotal += $item->product->price * $item->quantity;
            }
        }

        return view('public.products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $request->category_id,
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }

    public function index()
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        $products = Product::where('shop_id', $shop->id)
                           ->with('category')
                           ->latest()
                           ->get();
        
        return view('vendor.products.index', ['products' => $products]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('vendor.products.create', ['categories' => $categories]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $shop = Auth::user()->shop;
        if (!$shop || $shop->status != 'approved') {
            return redirect('/')->with('error', 'Toko Anda belum disetujui untuk menambah produk.');
        }

        $validatedData['shop_id'] = $shop->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            $filename = 'products/' . uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

            $imageResized = Image::make($image->getRealPath())
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 80);

            Storage::disk('public')->put($filename, (string) $imageResized);
            
            $validatedData['image'] = $filename;
        }

        Product::create($validatedData);

        return redirect('vendor/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        if ($product->shop->status != 'approved') {
            return redirect()->route('products.index')->with('error', 'Produk tidak tersedia.');
        }

        $product->load(['shop', 'category']);

        $averageRating = 0;
        $totalFeedbacks = 0;
        
        try {
            if (Schema::hasTable('feedbacks')) {
                $product->load(['feedbacks.user']);
                $averageRating = $product->feedbacks->avg('rating') ?? 0;
                $totalFeedbacks = $product->feedbacks->count();
            } else {
                $product->setRelation('feedbacks', collect());
            }
        } catch (\Exception $e) {
            $product->setRelation('feedbacks', collect());
        }

        try {
            if (Schema::hasTable('product_questions')) {
                $product->load(['productQuestions.user']);
            } else {
                $product->setRelation('productQuestions', collect());
            }
        } catch (\Exception $e) {
            $product->setRelation('productQuestions', collect());
        }

        $hasPurchased = false;
        $hasReviewed = false;

        if (Auth::check() && Auth::user()->role == 'user') {
            $user = Auth::user();

            $hasPurchased = Order::where('user_id', $user->id)
                                 ->where('status', 'completed')
                                 ->whereHas('orderItems', function ($query) use ($product) {
                                     $query->where('product_id', $product->id);
                                 })
                                 ->exists();

            $hasReviewed = Feedback::where('user_id', $user->id)
                                   ->where('product_id', $product->id)
                                   ->exists();
        }

        return view('public.products.show', [
            'product' => $product,
            'averageRating' => $averageRating,
            'totalFeedbacks' => $totalFeedbacks,
            'hasPurchased' => $hasPurchased,
            'hasReviewed' => $hasReviewed, 
        ]);
    }

    public function edit(Product $product)
    {
        $shop = Auth::user()->shop;
        
        if (!$shop || $product->shop_id != $shop->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Anda tidak memiliki izin untuk mengedit produk ini.');
        }

        $categories = Category::all();
        return view('vendor.products.edit', ['product' => $product, 'categories' => $categories]);
    }

    public function update(Request $request, Product $product)
    {
        $shop = Auth::user()->shop;
        if (!$shop || $product->shop_id != $shop->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Anda tidak memiliki izin untuk mengupdate produk ini.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $image = $request->file('image');
            $filename = 'products/' . uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

            $imageResized = Image::make($image->getRealPath())
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 80);

            Storage::disk('public')->put($filename, (string) $imageResized);
            $validatedData['image'] = $filename;
        }

        $product->update($validatedData);

        return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $shop = Auth::user()->shop;
        
        if (!$shop || $product->shop_id != $shop->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Anda tidak memiliki izin untuk menghapus produk ini.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}