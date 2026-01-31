<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary text-xs uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
