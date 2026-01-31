<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($appearance) && $appearance->business_name ? $appearance->business_name : config('app.name', 'CarWash App') }}</title>

        @php
            $faviconPath = public_path('favicon.ico');
            $faviconVersion = file_exists($faviconPath) ? filemtime($faviconPath) : null;
        @endphp
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}@if($faviconVersion)?v={{ $faviconVersion }}@endif">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="app-shell relative">
            <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/30 blur-3xl animate-float"></div>
                <div class="absolute top-40 -left-16 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl animate-pulse-soft"></div>
                <div class="absolute bottom-0 right-10 h-80 w-80 rounded-full bg-teal-400/20 blur-3xl animate-float"></div>
            </div>
            <div x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true" @close-sidebar.window="sidebarOpen = false" class="relative flex flex-1 flex-col md:flex-row">
                <div x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-30 md:hidden" @click="sidebarOpen = false"></div>
                @include('layouts.sidebar')

                <div class="flex flex-1 flex-col">
                    @include('layouts.navigation')
                    @include('partials.flash-messages')

                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="mx-4 mt-6 md:mx-8">
                            <div class="glass-panel rounded-3xl px-6 py-5">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="flex-grow px-4 pb-12 pt-6 md:px-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>

        <footer class="mt-auto border-t border-white/10 bg-slate-950/70 py-6 text-sm text-slate-200">
            <div class="max-w-7xl mx-auto px-4 flex flex-col items-center justify-between gap-3 sm:flex-row">
                <span>&copy; {{ date('Y') }} {{ $appearance->business_name ?? 'CarWash App' }}. Todos los derechos reservados.</span>
                <img src="{{ asset('images/signature.png') }}" alt="Firma" class="h-10">
            </div>
        </footer>
    </div>

    @if (session('print_ticket_id'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let ticketId = "{{ session('print_ticket_id') }}";
                let url = "{{ url('/tickets/print') }}/" + ticketId;

                let printTab = window.open(url, '_blank');

                if (printTab) {
                    printTab.focus();
                } else {
                    alert("Por favor habilita las ventanas emergentes para imprimir el ticket automáticamente.");
                }
            });
        </script>
    @endif
</body>
</html>
