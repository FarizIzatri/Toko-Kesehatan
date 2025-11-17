@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-th-border/50 border-th-border text-th-foreground rounded-lg p-2.5 focus:ring-1 focus:ring-th-foreground focus:border-th-foreground disabled:opacity-50']) }}>
