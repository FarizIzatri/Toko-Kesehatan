@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-th-muted mb-1']) }}>
    {{ $value ?? $slot }}
</label>
