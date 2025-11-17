<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-th-foreground text-th-background font-bold rounded-lg hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-th-foreground focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
