<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorTransaction;
use App\Models\Shop;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorTransaction::query()->with('shop');

        // Filter berdasarkan Toko (jika ada)
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Filter berdasarkan bulan (jika ada)
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year ?? date('Y'));
        }

        $transactions = $query->latest()->get();

        // Hitung total
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        // Ambil semua toko untuk filter
        $shops = Shop::where('status', 'approved')->get();

        return view('admin.reports.index', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'shops' => $shops,
        ]);
    }
}