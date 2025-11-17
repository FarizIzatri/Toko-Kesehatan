<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\VendorTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'vendor') {
            return $this->vendorDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Statistik utama
        $stats = [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalVendors' => User::where('role', 'vendor')->count(),
            'totalShops' => Shop::count(),
            'pendingShops' => Shop::where('status', 'pending')->count(),
            'approvedShops' => Shop::where('status', 'approved')->count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'monthlyRevenue' => Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('total_amount'),
            'totalRevenue' => Order::where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'paidOrders' => Order::where('status', 'paid')->count(),
            'shippedOrders' => Order::where('status', 'shipped')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'cancelledOrders' => Order::where('status', 'cancelled')->count(),
            'totalCategories' => Category::count(),
        ];
        
        // Data tambahan
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        $pendingShopsList = Shop::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', [
            'stats' => $stats,
            'role' => 'admin',
            'recentOrders' => $recentOrders,
            'pendingShopsList' => $pendingShopsList,
        ]);
    }

    private function vendorDashboard()
    {
        $shop = Auth::user()->shop;
        
        if (!$shop) {
            return view('dashboard', [
                'role' => 'vendor',
                'noShop' => true
            ]);
        }
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get vendor's order IDs
        $orderIds = OrderItem::where('shop_id', $shop->id)
            ->pluck('order_id')
            ->unique();
        
        // Calculate vendor revenue for current month
        $monthlyRevenue = 0;
        $monthlyOrders = Order::whereIn('id', $orderIds)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        foreach ($monthlyOrders as $order) {
            $order->load(['orderItems' => function($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            }]);
            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
            $monthlyRevenue += $myOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        }
        
        // Calculate total revenue
        $totalRevenue = 0;
        $allOrders = Order::whereIn('id', $orderIds)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        foreach ($allOrders as $order) {
            $order->load(['orderItems' => function($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            }]);
            $myOrderItems = $order->orderItems->where('shop_id', $shop->id);
            $totalRevenue += $myOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        }
        
        // Count orders by status for this vendor
        $vendorOrders = Order::whereIn('id', $orderIds)->get();
        $pendingOrders = 0;
        $paidOrders = 0;
        $shippedOrders = 0;
        $completedOrders = 0;
        
        foreach ($vendorOrders as $order) {
            $hasMyProducts = OrderItem::where('order_id', $order->id)
                ->where('shop_id', $shop->id)
                ->exists();
            
            if ($hasMyProducts) {
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
                }
            }
        }
        
        // Get top products (by quantity sold) - skip for now as it requires aggregation
        $topProducts = collect([]);
        
        // Get low stock products
        $lowStockProducts = Product::where('shop_id', $shop->id)
            ->where('stock', '<', 10)
            ->latest()
            ->take(5)
            ->get();
        
        // Get items sold this month
        $itemsSoldThisMonth = OrderItem::where('shop_id', $shop->id)
            ->whereHas('order', function($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', $currentMonth)
                      ->whereYear('created_at', $currentYear)
                      ->where('status', '!=', 'cancelled');
            })
            ->sum('quantity');
        
        $stats = [
            'totalProducts' => Product::where('shop_id', $shop->id)->count(),
            'totalOrders' => Order::whereIn('id', $orderIds)->count(),
            'pendingOrders' => $pendingOrders,
            'paidOrders' => $paidOrders,
            'shippedOrders' => $shippedOrders,
            'completedOrders' => $completedOrders,
            'monthlyRevenue' => $monthlyRevenue,
            'totalRevenue' => $totalRevenue,
            'itemsSoldThisMonth' => $itemsSoldThisMonth,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
        ];
        
        // Recent orders that need action
        $recentOrders = Order::whereIn('id', $orderIds)
            ->whereIn('status', ['pending', 'paid'])
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', [
            'stats' => $stats,
            'role' => 'vendor',
            'shop' => $shop,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function userDashboard()
    {
        $user = Auth::user();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Calculate statistics
        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'paid', 'shipped'])
            ->count();
        
        $totalOrders = Order::where('user_id', $user->id)->count();
        
        $cartItems = CartItem::where('user_id', $user->id)->count();
        
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $monthlySpent = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');
        
        $stats = [
            'activeOrders' => $activeOrders,
            'totalOrders' => $totalOrders,
            'cartItems' => $cartItems,
            'totalSpent' => $totalSpent,
            'monthlySpent' => $monthlySpent,
        ];
        
        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('orderItems')
            ->latest()
            ->take(5)
            ->get();
        
        // Orders that need payment
        $pendingPaymentOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', [
            'stats' => $stats,
            'role' => 'user',
            'recentOrders' => $recentOrders,
            'pendingPaymentOrders' => $pendingPaymentOrders,
        ]);
    }
}

