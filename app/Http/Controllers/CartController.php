<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        //Ambil ID user yang login
        $userId = Auth::id();

        //Ambil semua item keranjang milik user,
        $cartItems = CartItem::where('user_id', $userId)
                             ->with('product') // Ini Eager Loading, mengambil relasi 'product'
                             ->get();

        //Hitung total harga
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        //Kirim data ke view
        return view('user.cart.index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }

    public function store(Request $request)
    {
        //Validasi input (product_id)
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $userId = Auth::id();

        //Cek apakah produk ini sudah ada di keranjang user
        $cartItem = CartItem::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        //Ambil data produk untuk cek stok
        $product = Product::find($productId);
        $quantityToBuy = 1; 

        if ($product->stock < $quantityToBuy) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        if ($cartItem) {
            //jIKA SUDAH ADA: Tambah quantity-nya
            $newQuantity = $cartItem->quantity + $quantityToBuy;
            
            if ($product->stock < $newQuantity) {
                 return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah ini.');
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantityToBuy,
            ]);
        }

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.'
            ]);
        }

        // 6. Redirect
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        //Security Check: memastikan user yang login adalah pemilik item ini
        if ($cartItem->user_id != Auth::id()) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $cartItem->delete();

        // Redirect ke checkout jika request dari checkout, otherwise ke cart index
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'checkout') {
            return redirect()->route('checkout.create')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        //Redirect
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

}
