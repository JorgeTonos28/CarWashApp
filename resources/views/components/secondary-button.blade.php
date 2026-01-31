<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary text-xs uppercase tracking-wider shadow-sm focus:outline-none focus:ring-2 focus:ring-white/40 focus:ring-offset-2 disabled:opacity-40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
