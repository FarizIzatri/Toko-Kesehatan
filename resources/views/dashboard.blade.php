<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-th-foreground leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($role === 'admin')
                {{-- ADMIN DASHBOARD --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-th-foreground mb-6">Dashboard Admin</h1>
                    
                    {{-- Statistik Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {{-- Total Users --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Users</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalUsers'] }}</p>
                                </div>
                                <div class="bg-blue-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Vendors --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Vendors</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalVendors'] }}</p>
                                </div>
                                <div class="bg-green-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Products --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Products</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalProducts'] }}</p>
                                </div>
                                <div class="bg-purple-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Orders</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalOrders'] }}</p>
                                </div>
                                <div class="bg-yellow-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Monthly Revenue --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Pendapatan Bulan Ini</p>
                                    <p class="text-2xl font-bold text-th-foreground">Rp {{ number_format($stats['monthlyRevenue'], 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-green-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Revenue --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Pendapatan</p>
                                    <p class="text-2xl font-bold text-th-foreground">Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-cyan-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Pending Shop Approvals --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Toko Pending</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['pendingShops'] }}</p>
                                </div>
                                <div class="bg-orange-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Categories --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Categories</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalCategories'] }}</p>
                                </div>
                                <div class="bg-pink-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status Orders Breakdown --}}
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4 text-center">
                            <p class="text-sm text-yellow-300 mb-1">Pending</p>
                            <p class="text-xl font-bold text-yellow-300">{{ $stats['pendingOrders'] }}</p>
                        </div>
                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 text-center">
                            <p class="text-sm text-blue-300 mb-1">Paid</p>
                            <p class="text-xl font-bold text-blue-300">{{ $stats['paidOrders'] }}</p>
                        </div>
                        <div class="bg-cyan-500/10 border border-cyan-500/20 rounded-lg p-4 text-center">
                            <p class="text-sm text-cyan-300 mb-1">Shipped</p>
                            <p class="text-xl font-bold text-cyan-300">{{ $stats['shippedOrders'] }}</p>
                        </div>
                        <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 text-center">
                            <p class="text-sm text-green-300 mb-1">Completed</p>
                            <p class="text-xl font-bold text-green-300">{{ $stats['completedOrders'] }}</p>
                        </div>
                        <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 text-center">
                            <p class="text-sm text-red-300 mb-1">Cancelled</p>
                            <p class="text-xl font-bold text-red-300">{{ $stats['cancelledOrders'] }}</p>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Quick Links</h2>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.categories.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Manajemen Kategori
                            </a>
                            <a href="{{ route('admin.shops.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Manajemen Toko
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Manajemen Pesanan
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Laporan
                            </a>
                        </div>
                    </div>

                    {{-- Pending Shops --}}
                    @if($pendingShopsList->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Toko yang Perlu Approval</h2>
                        <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-th-border">
                                <thead class="bg-th-border/20">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Nama Toko</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Pemilik</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Kota</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-th-border">
                                    @foreach($pendingShopsList as $shop)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-th-foreground">{{ $shop->name }}</td>
                                        <td class="px-4 py-3 text-sm text-th-muted">{{ $shop->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-th-muted">{{ $shop->city ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('admin.shops.index') }}" class="text-blue-300 hover:text-blue-200">Review</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Recent Orders --}}
                    @if($recentOrders->count() > 0)
                    <div>
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Pesanan Terbaru</h2>
                        <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-th-border">
                                <thead class="bg-th-border/20">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Pemesan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-th-border">
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-th-foreground">#{{ $order->id }}</td>
                                        <td class="px-4 py-3 text-sm text-th-foreground">{{ $order->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-th-foreground">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-500/10 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-300">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-th-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

            @elseif($role === 'vendor')
                {{-- VENDOR DASHBOARD --}}
                @if(isset($noShop) && $noShop)
                    <div class="bg-th-border/20 border border-th-border rounded-lg p-8 text-center">
                        <p class="text-th-muted mb-4">Anda belum memiliki toko. Silakan buat toko terlebih dahulu.</p>
                        <a href="{{ route('shops.create') }}" class="inline-block bg-blue-500/10 text-blue-300 font-medium rounded-lg px-4 py-2 hover:bg-blue-500/20 transition-colors">
                            Buat Toko
                        </a>
                    </div>
                @else
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-th-foreground mb-2">Dashboard Toko: {{ $shop->name }}</h1>
                        <p class="text-th-muted mb-6">{{ $shop->description ?? 'Tidak ada deskripsi' }}</p>
                        
                        {{-- Statistik Cards --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            {{-- Total Products --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Total Produk</p>
                                        <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalProducts'] }}</p>
                                    </div>
                                    <div class="bg-purple-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Orders --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Total Pesanan</p>
                                        <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalOrders'] }}</p>
                                    </div>
                                    <div class="bg-yellow-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Monthly Revenue --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Pendapatan Bulan Ini</p>
                                        <p class="text-2xl font-bold text-th-foreground">Rp {{ number_format($stats['monthlyRevenue'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-green-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Items Sold This Month --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Item Terjual (Bulan Ini)</p>
                                        <p class="text-2xl font-bold text-th-foreground">{{ $stats['itemsSoldThisMonth'] }}</p>
                                    </div>
                                    <div class="bg-cyan-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Pending Orders --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Pesanan Pending</p>
                                        <p class="text-2xl font-bold text-th-foreground">{{ $stats['pendingOrders'] }}</p>
                                    </div>
                                    <div class="bg-yellow-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Revenue --}}
                            <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-th-muted mb-1">Total Pendapatan</p>
                                        <p class="text-2xl font-bold text-th-foreground">Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-green-500/10 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status Orders Breakdown --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4 text-center">
                                <p class="text-sm text-yellow-300 mb-1">Pending</p>
                                <p class="text-xl font-bold text-yellow-300">{{ $stats['pendingOrders'] }}</p>
                            </div>
                            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 text-center">
                                <p class="text-sm text-blue-300 mb-1">Paid</p>
                                <p class="text-xl font-bold text-blue-300">{{ $stats['paidOrders'] }}</p>
                            </div>
                            <div class="bg-cyan-500/10 border border-cyan-500/20 rounded-lg p-4 text-center">
                                <p class="text-sm text-cyan-300 mb-1">Shipped</p>
                                <p class="text-xl font-bold text-cyan-300">{{ $stats['shippedOrders'] }}</p>
                            </div>
                            <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 text-center">
                                <p class="text-sm text-green-300 mb-1">Completed</p>
                                <p class="text-xl font-bold text-green-300">{{ $stats['completedOrders'] }}</p>
                            </div>
                        </div>

                        {{-- Quick Links --}}
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-th-foreground mb-4">Quick Links</h2>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('vendor.products.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                    Kelola Produk
                                </a>
                                <a href="{{ route('vendor.orders.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                    Manajemen Pesanan
                                </a>
                                <a href="{{ route('vendor.reports.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                    Laporan
                                </a>
                            </div>
                        </div>

                        {{-- Low Stock Products --}}
                        @if($stats['lowStockProducts']->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-th-foreground mb-4">Produk Stok Rendah</h2>
                            <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-th-border">
                                    <thead class="bg-th-border/20">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Produk</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Stok</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-th-border">
                                        @foreach($stats['lowStockProducts'] as $product)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-th-foreground">{{ $product->name }}</td>
                                            <td class="px-4 py-3 text-sm text-red-300 font-semibold">{{ $product->stock }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('vendor.products.edit', $product->id) }}" class="text-blue-300 hover:text-blue-200">Edit</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- Recent Orders Needing Action --}}
                        @if($recentOrders->count() > 0)
                        <div>
                            <h2 class="text-lg font-semibold text-th-foreground mb-4">Pesanan yang Perlu Tindakan</h2>
                            <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-th-border">
                                    <thead class="bg-th-border/20">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">ID</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Pemesan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-th-border">
                                        @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-th-foreground">#{{ $order->id }}</td>
                                            <td class="px-4 py-3 text-sm text-th-foreground">{{ $order->user->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status === 'paid' ? 'blue' : 'yellow' }}-500/10 text-{{ $order->status === 'paid' ? 'blue' : 'yellow' }}-300">
                                                    {{ strtoupper($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('vendor.orders.index') }}" class="text-blue-300 hover:text-blue-200">Kelola</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif

            @else
                {{-- USER DASHBOARD --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-th-foreground mb-6">Dashboard Saya</h1>
                    
                    {{-- Statistik Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {{-- Active Orders --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Pesanan Aktif</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['activeOrders'] }}</p>
                                </div>
                                <div class="bg-blue-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Pesanan</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['totalOrders'] }}</p>
                                </div>
                                <div class="bg-green-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Items in Cart --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Items di Keranjang</p>
                                    <p class="text-2xl font-bold text-th-foreground">{{ $stats['cartItems'] }}</p>
                                </div>
                                <div class="bg-purple-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Spent --}}
                        <div class="bg-th-border/20 border border-th-border rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-th-muted mb-1">Total Belanja</p>
                                    <p class="text-2xl font-bold text-th-foreground">Rp {{ number_format($stats['totalSpent'], 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-cyan-500/10 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Quick Links</h2>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('products.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Katalog Produk
                            </a>
                            <a href="{{ route('cart.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Keranjang Saya
                            </a>
                            <a href="{{ route('orders.index') }}" class="bg-th-border/20 border border-th-border rounded-lg px-4 py-2 text-sm text-th-foreground hover:bg-th-border/30 transition-colors">
                                Pesanan Saya
                            </a>
                </div>
            </div>
        
                    {{-- Pending Payment Orders --}}
                    @if($pendingPaymentOrders->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Pesanan yang Perlu Pembayaran</h2>
                        <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-th-border">
                                <thead class="bg-th-border/20">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-th-border">
                                    @foreach($pendingPaymentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-th-foreground">#{{ $order->id }}</td>
                                        <td class="px-4 py-3 text-sm text-th-foreground">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm text-th-muted">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('orders.pay', $order->id) }}" class="text-blue-300 hover:text-blue-200">Bayar Sekarang</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Recent Orders --}}
                    @if($recentOrders->count() > 0)
                    <div>
                        <h2 class="text-lg font-semibold text-th-foreground mb-4">Pesanan Terbaru</h2>
                        <div class="bg-th-border/20 border border-th-border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-th-border">
                                <thead class="bg-th-border/20">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-th-muted uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-th-border">
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-th-foreground">#{{ $order->id }}</td>
                                        <td class="px-4 py-3 text-sm text-th-foreground">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-500/10 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'cancelled' ? 'red' : 'yellow') }}-300">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-th-muted">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('orders.index') }}" class="text-blue-300 hover:text-blue-200">Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            @endif

        </div>
        </div>
</x-app-layout>
