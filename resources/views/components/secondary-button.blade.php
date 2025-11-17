<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-th-border/50 text-th-muted font-medium rounded-lg hover:bg-th-border/70 transition-colors focus:outline-none focus:ring-2 focus:ring-th-foreground focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
