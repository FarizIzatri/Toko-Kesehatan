<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <h1 class="text-2xl font-semibold text-th-foreground mb-6">Profil Saya</h1>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-500/10 p-4 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-6">
            <div class="border border-th-border rounded-lg p-5 sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="border border-th-border rounded-lg p-5 sm:p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="border border-th-border rounded-lg p-5 sm:p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
