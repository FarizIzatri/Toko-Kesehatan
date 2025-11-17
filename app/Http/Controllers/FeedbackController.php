<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\Order;

class FeedbackController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // 2. Validasi Logika: Apakah user sudah membeli produk ini?
        $hasPurchased = Order::where('user_id', $user->id)
                             ->where('status', 'completed')
                             ->whereHas('orderItems', function ($query) use ($product) {
                                 $query->where('product_id', $product->id);
                             })
                             ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Anda hanya dapat memberi ulasan untuk produk yang sudah Anda beli.');
        }

        // 3. Validasi Logika: Apakah user sudah pernah memberi ulasan?
        $hasReviewed = Feedback::where('user_id', $user->id)
                               ->where('product_id', $product->id)
                               ->exists();
        
        if ($hasReviewed) {
            return back()->with('error', 'Anda sudah pernah memberi ulasan untuk produk ini.');
        }

        // 4. Jika lolos semua validasi, simpan feedback
        $product->feedbacks()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan Anda berhasil dikirim. Terima kasih!');
    }
}