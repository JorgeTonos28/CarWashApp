@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold uppercase tracking-widest text-slate-500']) }}>
    {{ $value ?? $slot }}
</label>
