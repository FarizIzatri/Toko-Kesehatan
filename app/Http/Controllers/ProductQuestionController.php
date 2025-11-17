<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductQuestion;

class ProductQuestionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $product->productQuestions()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Pertanyaan Anda berhasil dikirim.');
    }


    public function reply(Request $request, ProductQuestion $question)
    {
        $request->validate([
            'reply' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();
        $product = $question->product;

        if ($user->role != 'vendor' || $user->shop->id != $product->shop_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk membalas pertanyaan ini.');
        }

        if ($question->reply) {
            return back()->with('error', 'Pertanyaan ini sudah pernah dijawab.');
        }

        $question->update([
            'reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Balasan Anda berhasil dikirim.');
    }
}