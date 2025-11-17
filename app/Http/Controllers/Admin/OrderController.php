<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Ambil semua order,
        $orders = Order::with('user')
                       ->latest() 
                       ->get();

        //Kirim data ke view admin
        return view('admin.orders.index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * Catatan: Admin tidak memiliki wewenang untuk mengubah status order.
     * Hanya vendor yang memiliki wewenang untuk approve/cancel order.
     */
    public function update(Request $request, Order $order)
    {
        // Admin tidak memiliki wewenang untuk mengubah status order
        return redirect()->route('admin.orders.index')->with('error', 'Admin tidak memiliki wewenang untuk mengubah status pesanan. Silakan hubungi vendor terkait.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Download summary report of orders (monthly revenue, statistics, etc.)
     */
    public function downloadSummary(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Get all orders for the selected month
        $orders = Order::with('user')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->where('status', '!=', 'cancelled')->sum('total_amount');
        $pendingOrders = $orders->where('status', 'pending')->count();
        $paidOrders = $orders->where('status', 'paid')->count();
        $shippedOrders = $orders->where('status', 'shipped')->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();

        // Revenue by payment method
        $revenueByPayment = $orders->where('status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->map(function ($group) {
                return $group->sum('total_amount');
            });

        // Monthly comparison (previous month)
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $prevMonthOrders = Order::whereYear('created_at', $prevMonth->year)
            ->whereMonth('created_at', $prevMonth->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthName = Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY');
        $prevMonthName = $prevMonth->locale('id')->isoFormat('MMMM YYYY');

        $data = [
            'orders' => $orders,
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
            'prevMonthRevenue' => $prevMonthOrders,
            'prevMonthName' => $prevMonthName,
            'isAdmin' => true,
        ];

        $pdf = Pdf::loadView('pdf.orders-summary', $data);
        $filename = 'rangkuman-pesanan-' . strtolower(str_replace(' ', '-', $monthName)) . '.pdf';
        
        return $pdf->download($filename);
    }
}
