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
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-10 sm:pt-0">
            <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/30 blur-3xl animate-float"></div>
                <div class="absolute top-40 -left-16 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl animate-pulse-soft"></div>
            </div>
            <div class="relative flex justify-center mb-6">
                <a href="/">
                    @if(isset($appearance) && $appearance->login_logo_updated_at)
                        <img src="{{ asset('images/login_logo.png') }}?v={{ $appearance->login_logo_updated_at->timestamp }}" class="w-24 h-24 object-contain drop-shadow-xl" alt="Logo de inicio">
                    @else
                        <x-application-logo class="w-20 h-20 fill-current text-white" />
                    @endif
                </a>
            </div>

            <div class="relative w-full sm:max-w-md px-6 py-6 glass-panel rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
