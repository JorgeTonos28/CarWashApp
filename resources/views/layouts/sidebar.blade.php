<aside :class="{'translate-x-0': sidebarOpen}" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full transform bg-slate-950/90 backdrop-blur-2xl transition-transform duration-200 md:relative md:translate-x-0">
    <div class="flex h-full flex-col gap-6 overflow-y-auto px-5 py-6">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-500/20 text-cyan-200">
                <i class="fa-solid fa-water"></i>
            </span>
            <div>
                <p class="text-sm font-semibold text-white">{{ $appearance->business_name ?? 'CarWash App' }}</p>
                <p class="text-xs text-white/60">Control Operativo</p>
            </div>
        </div>
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                Panel
            </a>
            <a href="{{ route('petty-cash.index') }}" class="sidebar-link {{ request()->routeIs('petty-cash.*') ? 'sidebar-link-active' : '' }}">
                <i class="fa-solid fa-cash-register"></i>
                Caja Chica
            </a>
            <a href="{{ route('washers.index') }}" class="sidebar-link {{ request()->routeIs('washers.*') ? 'sidebar-link-active' : '' }}">
                <i class="fa-solid fa-people-group"></i>
                Lavadores
            </a>
            <div x-data="{ open: {{ request()->routeIs('services.*','products.*','drinks.*') ? 'true' : 'false' }} }">
                <button type="button" @click="open=!open" class="sidebar-link w-full justify-between {{ request()->routeIs('services.*','products.*','drinks.*') ? 'sidebar-link-active' : '' }}">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-layer-group"></i>
                        Catálogo
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-cloak class="mt-2 space-y-1 pl-4">
                    <a href="{{ route('services.index') }}" class="sidebar-link {{ request()->routeIs('services.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        Servicios
                    </a>
                    <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-box-open"></i>
                        Productos
                    </a>
                    <a href="{{ route('drinks.index') }}" class="sidebar-link {{ request()->routeIs('drinks.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-martini-glass-citrus"></i>
                        Tragos
                    </a>
                </div>
            </div>
            <div x-data="{ open: {{ request()->routeIs('tickets.*') ? 'true' : 'false' }} }">
                <button type="button" @click="open=!open" class="sidebar-link w-full justify-between {{ request()->routeIs('tickets.*') ? 'sidebar-link-active' : '' }}">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-receipt"></i>
                        Tickets
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-cloak class="mt-2 space-y-1 pl-4">
                    <a href="{{ route('tickets.index') }}" class="sidebar-link {{ request()->routeIs('tickets.index') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-circle-check"></i>
                        Activos
                    </a>
                    <a href="{{ route('tickets.canceled') }}" class="sidebar-link {{ request()->routeIs('tickets.canceled') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-circle-xmark"></i>
                        Cancelados
                    </a>
                </div>
            </div>
            <div x-data="{ open: {{ request()->routeIs('customers.*','vehicle-registry.*') ? 'true' : 'false' }} }">
                <button type="button" @click="open=!open" class="sidebar-link w-full justify-between {{ request()->routeIs('customers.*','vehicle-registry.*') ? 'sidebar-link-active' : '' }}">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-people-arrows"></i>
                        Clientes y Vehículos
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-cloak class="mt-2 space-y-1 pl-4">
                    <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-user-group"></i>
                        Clientes
                    </a>
                    <a href="{{ route('vehicle-registry.index') }}" class="sidebar-link {{ request()->routeIs('vehicle-registry.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-car-side"></i>
                        Parque Vehicular
                    </a>
                </div>
            </div>
            @if(auth()->user()->role === 'admin')
            <div x-data="{ open: {{ request()->routeIs('discounts.*','users.*','bank-accounts.*','appearance.*','settings.*') ? 'true' : 'false' }} }">
                <button type="button" @click="open=!open" class="sidebar-link w-full justify-between {{ request()->routeIs('discounts.*','users.*','bank-accounts.*','appearance.*','settings.*') ? 'sidebar-link-active' : '' }}">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-gear"></i>
                        Configuración
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-cloak class="mt-2 space-y-1 pl-4">
                    <a href="{{ route('discounts.index') }}" class="sidebar-link {{ request()->routeIs('discounts.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-tags"></i>
                        Descuentos
                    </a>
                    <a href="{{ route('bank-accounts.index') }}" class="sidebar-link {{ request()->routeIs('bank-accounts.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-building-columns"></i>
                        Cuentas Bancarias
                    </a>
                    <a href="{{ route('appearance.index') }}" class="sidebar-link {{ request()->routeIs('appearance.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-palette"></i>
                        Apariencia
                    </a>
                    <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        Ajustes
                    </a>
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        Usuarios
                    </a>
                </div>
            </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'sidebar-link-active' : '' }}">
                <i class="fa-solid fa-id-badge"></i>
                Perfil
            </a>
        </nav>
        <div class="mt-auto rounded-2xl border border-white/10 bg-white/5 p-4 text-xs text-white/70">
            <p class="font-semibold text-white">Tip del día</p>
            <p>Activa alertas para stock bajo y mantén el flujo eficiente.</p>
        </div>
    </div>
</aside>
