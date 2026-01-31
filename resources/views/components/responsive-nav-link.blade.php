@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex w-full items-center rounded-2xl bg-white/15 px-4 py-3 text-start text-sm font-semibold text-white shadow-lg shadow-cyan-500/10 transition'
            : 'flex w-full items-center rounded-2xl px-4 py-3 text-start text-sm font-semibold text-white/70 transition hover:bg-white/10 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
