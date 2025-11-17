<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-th-foreground text-center">Masuk ke Akun</h2>
        <p class="text-sm text-th-muted text-center mt-2">Silakan masuk untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 rounded-lg bg-green-500/10 p-3 text-sm text-green-300" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-500/10 p-3 text-sm text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded bg-th-border/50 border-th-border text-th-foreground focus:ring-th-foreground" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-th-muted">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-th-muted hover:text-th-foreground transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-th-muted">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-th-foreground hover:underline font-medium">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
