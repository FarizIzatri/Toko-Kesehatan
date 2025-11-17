<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorTransaction;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $shopId = Auth::user()->shop->id;

        // Ambil data transaksi hanya untuk toko ini
        $query = VendorTransaction::where('shop_id', $shopId);

        // Filter berdasarkan bulan (jika ada)
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year ?? date('Y'));
        }

        $transactions = $query->latest()->get();

        // Hitung total
        $totalRevenue = $transactions->sum('total_amount');
        $totalItems = $transactions->sum('quantity');

        return view('vendor.reports.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalItems' => $totalItems,
        ]);
    }
}