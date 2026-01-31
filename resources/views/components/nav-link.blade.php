@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-pill bg-white/20 text-white shadow-lg shadow-cyan-500/10'
            : 'nav-pill text-white/70';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
