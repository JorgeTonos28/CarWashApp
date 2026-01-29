@if(isset($appearance) && $appearance->logo_updated_at)
    <img src="{{ asset('images/logo.png') }}?v={{ $appearance->logo_updated_at->timestamp }}" alt="Logo" {{ $attributes }}>
@else
    <svg viewBox="0 0 48 48" role="img" aria-label="Logo" {{ $attributes }}>
        <rect width="48" height="48" rx="10" fill="currentColor" />
        <path d="M14 26c0-6 4-12 10-12 6 0 10 6 10 12 0 6-4 10-10 10-6 0-10-4-10-10Z" fill="#fff" opacity="0.9"/>
        <path d="M18 26c0-4 3-8 6-8s6 4 6 8-3 6-6 6-6-2-6-6Z" fill="currentColor"/>
    </svg>
@endif
