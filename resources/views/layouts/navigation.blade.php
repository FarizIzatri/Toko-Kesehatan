@php
// Logika untuk kelas 'active' vs 'inactive' (versi ikon)
$baseClasses = 'flex justify-center items-center h-12 w-12 rounded-lg transition-colors duration-150 ease-in-out';
$activeClasses = $baseClasses . ' bg-th-border text-th-foreground';
$inactiveClasses = $baseClasses . ' text-th-muted hover:bg-th-border/50 hover:text-th-foreground';

// Helper SVG
function icon($svg) {
    return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">'.$svg.'</svg>';
}
@endphp

<div class="flex flex-col h-full justify-between items-center">
    
    <div class="space-y-4">
        <div class="flex justify-center items-center h-12 w-12 mb-4">
            <svg class="w-8 h-8 text-th-foreground" viewBox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 0C8.954 0 0 8.954 0 20s8.954 20 20 20 20-8.954 20-20S31.046 0 20 0zm0 36C11.163 36 4 28.837 4 20S11.163 4 20 4s16 7.163 16 16-7.163 16-16 16z"></path>
                <path d="M20 10c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10zm0 16c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z"></path>
            </svg>
        </div>

        <a href="{{ route('dashboard') }}" title="Home/Dashboard" @class([$activeClasses => request()->routeIs('dashboard'), $inactiveClasses => !request()->routeIs('dashboard')])>
            {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M10 16h.01"></path>') !!}
        </a>
        <a href="{{ route('products.index') }}" title="Katalog Produk" @class([$activeClasses => request()->routeIs('products.index'), $inactiveClasses => !request()->routeIs('products.index')])>
            {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>') !!}
        </a>

        @auth
            @if(auth()->user()->role == 'user')
                <a href="{{ route('cart.index') }}" title="Keranjang" @class([$activeClasses => request()->routeIs('cart.index'), $inactiveClasses => !request()->routeIs('cart.index')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>') !!}
                </a>
                <a href="{{ route('orders.index') }}" title="Pesanan Saya" @class([$activeClasses => request()->routeIs('orders.*'), $inactiveClasses => !request()->routeIs('orders.*')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>') !!}
                </a>
                <a href="{{ route('shops.create') }}" title="Buka Toko Baru" @class([$activeClasses => request()->routeIs('shops.create'), $inactiveClasses => !request()->routeIs('shops.create')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>') !!}
                </a>

            @elseif(auth()->user()->role == 'vendor')
                 <a href="{{ route('vendor.products.index') }}" title="Kelola Produk" @class([$activeClasses => request()->routeIs('vendor.products.*'), $inactiveClasses => !request()->routeIs('vendor.products.*')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>') !!}
                </a>
                <a href="{{ route('vendor.orders.index') }}" title="Kelola Pesanan" @class([$activeClasses => request()->routeIs('vendor.orders.*'), $inactiveClasses => !request()->routeIs('vendor.orders.*')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>') !!}
                </a>

            @elseif(auth()->user()->role == 'admin')
                <a href="{{ route('admin.categories.index') }}" title="Kelola Kategori" @class([$activeClasses => request()->routeIs('admin.categories.*'), $inactiveClasses => !request()->routeIs('admin.categories.*')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h.01M7 11h.01M16 7h.01M16 3h.01M16 11h.01M12 21h.01M12 17h.01M12 13h.01M3 3h18v18H3z"></path>') !!}
                </a>
                <a href="{{ route('admin.shops.index') }}" title="Kelola Toko" @class([$activeClasses => request()->routeIs('admin.shops.*'), $inactiveClasses => !request()->routeIs('admin.shops.*')])>
                    {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>') !!}
                </a>
                <a href="{{ route('admin.orders.index') }}" title="Kelola Pesanan" @class([$activeClasses => request()->routeIs('admin.orders.*'), $inactiveClasses => !request()->routeIs('admin.orders.*')])>
                   {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>') !!}
                </a>
            @endif
        @endauth
    </div>

    <div class="space-y-4">
    @auth
        <a href="{{ route('profile.edit') }}" title="Profil" @class([$activeClasses => request()->routeIs('profile.edit'), $inactiveClasses => !request()->routeIs('profile.edit')])>
            {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>') !!}
        </a>

        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <a href="{{ route('logout') }}"
               title="Logout"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="{{ $inactiveClasses }}">
               {!! icon('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>') !!}
            </a>
        </form>
    @endauth
    </div>
</div>