<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-th-background text-th-foreground">
        {{-- Top Bar (Mobile & Desktop) --}}
        <header class="sticky top-0 z-50 bg-th-background">
            <div class="flex items-center justify-between md:justify-center px-4 py-3">
                
                {{-- [DIUBAH] Spacer kiri (mobile only) w-10 untuk menyeimbangkan --}}
                <div class="w-10 md:hidden"></div>

                {{-- Logo (Center) --}}
                <a href="{{ route('home') }}" class="flex items-center justify-center">
                    <div class="w-10 h-10 rounded-full bg-th-foreground flex items-center justify-center">
                        <svg class="w-6 h-6 text-th-background" viewBox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 0C8.954 0 0 8.954 0 20s8.954 20 20 20 20-8.954 20-20S31.046 0 20 0zm0 36C11.163 36 4 28.837 4 20S11.163 4 20 4s16 7.163 16 16-7.163 16-16 16z"></path>
                            <path d="M20 10c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10zm0 16c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z"></path>
                        </svg>
                    </div>
                </a>

                {{-- Profile/Logout Menu (Right) --}}
                {{-- [DIUBAH] Container (mobile only) w-10 untuk menyeimbangkan --}}
                <div class="relative w-10 md:hidden">
                    <button id="profile-menu-btn" class="text-th-foreground focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-th-background border border-th-border rounded-lg shadow-lg py-2 z-50">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-th-foreground hover:bg-th-border/50">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-th-foreground hover:bg-th-border/50">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-th-foreground hover:bg-th-border/50">Login</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-th-foreground hover:bg-th-border/50">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <div class="min-h-screen flex">
            {{-- Sidebar (Desktop Only) --}}
            {{-- [DIUBAH] top-[73px] -> top-[4rem] dan h-[calc(100vh-73px)] -> h-[calc(100vh-4rem)] --}}
            <nav class="hidden md:block w-20 flex-shrink-0 sticky top-[4rem] h-[calc(100vh-4rem)] border-r border-th-border py-8 px-4">
                @include('layouts.navigation')
            </nav>

            {{-- Main Content --}}
            <div class="flex-1 overflow-y-auto pb-16 md:pb-0">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- Bottom Taskbar (Mobile Only) --}}
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-th-background border-t border-th-border z-50 h-16">
            <div class="flex justify-around items-center h-full">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-th-foreground' : 'text-th-muted' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M10 16h.01"></path>
                    </svg>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('products.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </a>
                @auth
                    @if(auth()->user()->role == 'user')
                        <a href="{{ route('cart.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('cart.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('orders.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </a>
                    @elseif(auth()->user()->role == 'vendor')
                        <a href="{{ route('vendor.products.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('vendor.products.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </a>
                        <a href="{{ route('vendor.orders.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('vendor.orders.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </a>
                        <a href="{{ route('vendor.reports.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('vendor.reports.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </a>
                    @elseif(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.categories.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.shops.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.shops.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.orders.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('admin.reports.*') ? 'text-th-foreground' : 'text-th-muted' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </a>
                    @endif
                @endauth
            </div>
        </nav>

        @stack('scripts')
        <script>
            // Profile menu toggle
            document.getElementById('profile-menu-btn')?.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = document.getElementById('profile-menu');
                menu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const profileBtn = document.getElementById('profile-menu-btn');
                const profileMenu = document.getElementById('profile-menu');
                if (profileBtn && profileMenu && !profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        </script>
    </body>
</html>