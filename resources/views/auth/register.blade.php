<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-th-foreground text-center">Buat Akun Baru</h2>
        <p class="text-sm text-th-muted text-center mt-2">Daftar untuk mulai berbelanja</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-500/10 p-3 text-sm text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-th-muted">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-th-foreground hover:underline font-medium">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
