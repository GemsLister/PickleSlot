@props([
    'href',
    'size' => 'md',
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3.5 text-base',
    ][$size] ?? $sizeClasses['md'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-xl bg-emerald-600 font-semibold text-white shadow-sm hover:bg-emerald-500 transition {$sizeClasses}"]) }}>
    {{ $slot }}
</a>
