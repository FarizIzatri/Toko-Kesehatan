<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Report;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = Order::where('user_id', $userId)
                      ->with(['orderItems', 'transactions', 'report']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        return view('user.orders.index', [
            'orders' => $orders,
            'selectedStatus' => $request->status ?? null
        ]);
    }

    public function create()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
                             ->with(['product.shop'])
                             ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        $shopCities = $cartItems->map(function($item) {
            return $item->product->shop->city ?? null;
        })->unique()->filter();

        $codAvailable = $shopCities->count() == 1;
        $shopCity = $shopCities->first();

        return view('user.checkout.create', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'codAvailable' => $codAvailable,
            'shopCity' => $shopCity
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:debit_credit,cod,bank_transfer',
            'user_city' => 'required_if:payment_method,cod|string|nullable',
        ]);

        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->with(['product.shop'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        // --- Validasi COD
        if ($validated['payment_method'] == 'cod') {
            $shopCities = $cartItems->map(function($item) {
                return $item->product->shop->city ?? null;
            })->unique()->filter();

            if ($shopCities->count() != 1) {
                return back()->with('error', 'COD hanya tersedia untuk produk dari toko yang sama.');
            }

            $shopCity = $shopCities->first();
            $userCity = $validated['user_city'] ?? '';

            if (strtolower(trim($userCity)) !== strtolower(trim($shopCity))) {
                return back()->with('error', 'COD hanya tersedia untuk pengiriman di kota yang sama dengan toko (' . $shopCity . ').');
            }
        }

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $product = $item->product;
                if ($product->stock < $item->quantity) {
                    DB::rollBack();
                    return back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
                }
                $totalPrice += $product->price * $item->quantity;
            }

            // Jika COD, status langsung 'paid' (siap kirim)
            // Jika bukan COD, status 'pending' (menunggu pembayaran)
            $orderStatus = $validated['payment_method'] == 'cod' ? 'paid' : 'pending';
            $transactionStatus = $validated['payment_method'] == 'cod' ? 'success' : 'pending';

            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $totalPrice,
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'status' => $orderStatus,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'shop_id' => $item->product->shop_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            Transaction::create([
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'provider' => $validated['payment_method'],
                'status' => $transactionStatus,
            ]);

            CartItem::where('user_id', $userId)->delete();
            DB::commit();

            if ($order->status == 'paid') {
                // Jika pesanan COD (langsung paid), redirect ke riwayat pesanan
                return redirect()->route('orders.index')->with('success', 'Pesanan COD Anda telah berhasil dibuat dan akan segera diproses.');
            } else {
                // Jika pesanan PREPAID (pending), redirect ke halaman pembayaran
                return redirect()->route('orders.pay', $order->id)->with('success', 'Pesanan Anda telah berhasil dibuat! Silakan selesaikan pembayaran.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function showPayment(Order $order)
    {
        // Validasi: Apakah user ini pemilik order?
        if (Auth::id() != $order->user_id) {
            return redirect()->route('orders.index')->with('error', 'Aksi tidak diizinkan.');
        }

        // Validasi: Apakah order masih 'pending'?
        if ($order->status != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah diproses.');
        }
        
        $transaction = $order->transactions()->first();

        // Tampilkan view pembayaran palsu
        return view('user.orders.pay', [
            'order' => $order,
            'transaction' => $transaction
        ]);
    }

    public function processPayment(Request $request, Order $order)
    {
        // Validasi: Apakah user ini pemilik order?
        if (Auth::id() != $order->user_id) {
            return redirect()->route('orders.index')->with('error', 'Aksi tidak diizinkan.');
        }

        // Validasi: Apakah order masih 'pending'?
        if ($order->status != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah diproses.');
        }

        // Update status Order dan Transaksi
        $order->update(['status' => 'paid']);
        $order->transactions()->update([
            'status' => 'success',
        ]);

        // Redirect ke riwayat pesanan
        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil! Pesanan Anda akan segera diproses oleh vendor.');
    }


    public function cancel(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id != $userId) {
            return redirect()->route('orders.index')->with('error', 'Anda tidak memiliki izin untuk membatalkan pesanan ini.');
        }

        if ($order->status != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan tidak dapat dibatalkan karena status sudah ' . $order->status . '.');
        }

        try {
            DB::beginTransaction();

            $orderItems = $order->orderItems()->with('product')->get();
            foreach ($orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
            $order->transactions()->update(['status' => 'failed']);

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan. Stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.index')->with('error', 'Terjadi kesalahan saat membatalkan pesanan: ' . $e->getMessage());
        }
    }

    public function resendInvoice(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id != $userId) {
            return redirect()->route('orders.index')->with('error', 'Anda tidak memiliki izin untuk mengakses pesanan ini.');
        }

        if (!in_array($order->status, ['shipped', 'completed'])) {
            return redirect()->route('orders.index')->with('error', 'Invoice hanya dapat dikirim untuk pesanan yang sudah dikirim atau selesai.');
        }

        try {
            // Load semua relasi yang dibutuhkan
            $order->load(['user', 'orderItems.product']);

            // Buat PDF dari view
            $pdf = Pdf::loadView('pdf.invoice', [
                'order' => $order
            ]);

            // Buat nama file unik dan simpan ke storage
            $path = 'invoices/order-' . $order->id . '_' . time() . '.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            // Kirim email
            Mail::to($order->user->email)->send(new InvoiceMail($order, $path));
            
            // Catat ke tabel 'reports'
            Report::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'email_delivery_status' => 'sent',
            ]);

            return redirect()->route('orders.index')->with('success', 'Invoice berhasil dikirim ulang ke email Anda.');

        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Terjadi kesalahan saat mengirim invoice: ' . $e->getMessage());
        }
    }

    public function complete(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id != $userId) {
            return redirect()->route('orders.index')->with('error', 'Anda tidak memiliki izin untuk mengakses pesanan ini.');
        }

        if ($order->status != 'shipped') {
            return redirect()->route('orders.index')->with('error', 'Hanya pesanan yang sudah dikirim yang dapat diselesaikan.');
        }

        try {
            $order->update(['status' => 'completed']);

            // Trigger event untuk mengirim invoice (jika belum pernah dikirim)
            event(new \App\Events\OrderCompleted($order));

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil diselesaikan. Invoice telah dikirim ke email Anda.');

        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Terjadi kesalahan saat menyelesaikan pesanan: ' . $e->getMessage());
        }
    }
}