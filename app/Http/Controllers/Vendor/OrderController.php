<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Events\OrderCompleted;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil toko milik vendor yang login
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        // Ambil semua order yang memiliki produk dari toko vendor ini
        $orderIds = OrderItem::where('shop_id', $shop->id)
                            ->pluck('order_id')
                            ->unique();

        $orders = Order::whereIn('id', $orderIds)
                      ->with(['user', 'orderItems' => function($query) use ($shop) {
                          $query->where('shop_id', $shop->id);
                      }])
                      ->latest()
                      ->get();

        return view('vendor.orders.index', ['orders' => $orders]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Pastikan vendor memiliki toko
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        // Pastikan order ini memiliki produk dari toko vendor ini
        $hasProductFromShop = OrderItem::where('order_id', $order->id)
                                       ->where('shop_id', $shop->id)
                                       ->exists();

        if (!$hasProductFromShop) {
            return redirect()->route('vendor.orders.index')->with('error', 'Anda tidak memiliki izin untuk mengupdate pesanan ini.');
        }

        // Validasi input (diubah sesuai instruksi: shipped, completed, cancelled)
        $request->validate([
            'status' => 'required|in:shipped,completed,cancelled',
        ]);

        $newStatus = $request->status;

        // Jika dibatalkan, kembalikan stok
        if ($newStatus == 'cancelled') {
            $orderItems = OrderItem::where('order_id', $order->id)
                                  ->where('shop_id', $shop->id)
                                  ->with('product')
                                  ->get();

            foreach ($orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        // Update status order
        $order->update([
            'status' => $newStatus
        ]);

        // Jika statusnya 'shipped' ATAU 'completed', picu event
        if ($newStatus == 'shipped' || $newStatus == 'completed') {
            // Panggil Event
            Event::dispatch(new OrderCompleted($order));
        }

        return redirect()->route('vendor.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Download summary report of orders for vendor's shop (monthly revenue, statistics, etc.)
     */
    public function downloadSummary(Request $request)
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Get order IDs that have products from this shop
        $orderIds = OrderItem::where('shop_id', $shop->id)
            ->pluck('order_id')
            ->unique();

        // Get orders for the selected month
        $orders = Order::whereIn('id', $orderIds)
            ->with(['user', 'orderItems' => function($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            }])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->get();

        // Calculate vendor-specific statistics
        $totalOrders = $orders->count();
        $totalRevenue = 0;
        $pendingOrders = 0;
        $paidOrders = 0;
        $shippedOrders = 0;
        $completedOrders = 0;
        $cancelledOrders = 0;

        foreach ($orders as $order) {
            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
            $myTotal = $myOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            if ($order->status != 'cancelled') {
                $totalRevenue += $myTotal;
            }

            switch ($order->status) {
                case 'pending':
                    $pendingOrders++;
                    break;
                case 'paid':
                    $paidOrders++;
                    break;
                case 'shipped':
                    $shippedOrders++;
                    break;
                case 'completed':
                    $completedOrders++;
                    break;
                case 'cancelled':
                    $cancelledOrders++;
                    break;
            }
        }

        // Revenue by payment method
        $revenueByPayment = [];
        foreach ($orders->where('status', '!=', 'cancelled') as $order) {
            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
            $myTotal = $myOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            $method = $order->payment_method;
            if (!isset($revenueByPayment[$method])) {
                $revenueByPayment[$method] = 0;
            }
            $revenueByPayment[$method] += $myTotal;
        }

        // Monthly comparison (previous month)
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $prevMonthOrderIds = OrderItem::where('shop_id', $shop->id)
            ->pluck('order_id')
            ->unique();
        $prevMonthOrders = Order::whereIn('id', $prevMonthOrderIds)
            ->whereYear('created_at', $prevMonth->year)
            ->whereMonth('created_at', $prevMonth->month)
            ->where('status', '!=', 'cancelled')
            ->get();

        $prevMonthRevenue = 0;
        foreach ($prevMonthOrders as $order) {
            $order->load(['orderItems' => function($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            }]);
            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
            $prevMonthRevenue += $myOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        }

        $monthName = Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY');
        $prevMonthName = $prevMonth->locale('id')->isoFormat('MMMM YYYY');

        $data = [
            'orders' => $orders,
            'shop' => $shop,
            'month' => $monthName,
            'year' => $year,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'paidOrders' => $paidOrders,
            'shippedOrders' => $shippedOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'revenueByPayment' => $revenueByPayment,
            'prevMonthRevenue' => $prevMonthRevenue,
            'prevMonthName' => $prevMonthName,
            'isAdmin' => false,
        ];

        $pdf = Pdf::loadView('pdf.orders-summary', $data);
        $filename = 'rangkuman-pesanan-' . $shop->name . '-' . strtolower(str_replace(' ', '-', $monthName)) . '.pdf';
        
        return $pdf->download($filename);
    }
}