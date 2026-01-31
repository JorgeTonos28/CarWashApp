@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700']) }}>
        {{ $status }}
    </div>
@endif
