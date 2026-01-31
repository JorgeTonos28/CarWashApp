<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-danger text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
