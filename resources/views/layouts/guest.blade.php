<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="mainTheme" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="dns-prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="preconnect" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prerender" href="{{ request()->getSchemeAndHttpHost() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ URL::current() }}">
    <meta name="title" content="{{ $title }}">

    <title>{{ $title ?? config('app.name', 'RestoApp') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Financial Services">
    <meta name="keywords" content="Financial Services">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="Financial Services">
    <meta property="og:url" content="/" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $title }}" />
    <meta name="author" content="{{ $title }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="robots" content="all,follow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=glass_cup" />
    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @vite(['resources/css/app.css'])
    <style>
        [x-cloak] {
            display: none;
        }

        @keyframes charge {
            0% {
                left: -100%;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                left: 100%;
                opacity: 0;
            }
        }

        .animate-charge {
            animation: charge 2s linear infinite;
        }
    </style>

    @stack('styles')
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @livewireScriptConfig
    @stack('scripts')
</head>

<body class="font-sans antialiased bg-retro-cream text-retro-blue">
    <div x-data="{ isOpen: false }" x-cloak>
        <!-- Header -->
        {{ $header ?? '' }}

        <!-- Main Content -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-retro-orange text-white py-4">
            <div class="container mx-auto text-center">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>

        <!-- Theme Switcher -->
        {{-- <x-theme-switcher /> --}}
    </div>
</body>


</html>
