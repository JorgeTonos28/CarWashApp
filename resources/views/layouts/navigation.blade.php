<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <button @click="$dispatch('open-sidebar')" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none md:hidden">
                    <i class="fa-solid fa-bars-staggered text-sm"></i>
                </button>
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-white">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold">{{ $appearance->business_name ?? 'CarWash App' }}</p>
                            <p class="text-[11px] text-white/60">Dashboard operativo</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center gap-3 sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                        {{ __('Panel') }}
                    </x-nav-link>
                    <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                        <i class="fa-solid fa-warehouse text-xs"></i>
                        {{ __('Inventario') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-black/20 transition hover:bg-white/20 focus:outline-none">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <div class="text-left">
                                <p class="text-sm">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-white/60">{{ Auth::user()->email }}</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-white/70"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none">
                    <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="space-y-2 px-4 pb-3 pt-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fa-solid fa-chart-line me-2 text-xs"></i>
                {{ __('Panel') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                <i class="fa-solid fa-warehouse me-2 text-xs"></i>
                {{ __('Inventario') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/10 px-4 pb-4 pt-3 text-white/80">
            <div class="space-y-1">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/60">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-4 space-y-2">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
