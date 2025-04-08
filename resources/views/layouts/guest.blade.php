<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="mainTheme" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="dns-prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- CSRF & SEO Meta Tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ URL::current() }}">
    <meta name="title" content="{{ $title ?? config('app.name', 'RestoApp') }}">
    <title>{{ $title ?? config('app.name', 'RestoApp') }}</title>

    <!-- Enhanced SEO -->
    <meta name="description"
        content="Order delicious meals online from {{ config('app.name') }} - Fast delivery, fresh ingredients, and authentic flavors. Browse our menu and order now!">
    <meta name="keywords"
        content="food delivery, online ordering, restaurant, meals, takeout, {{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'RestoApp') }}">
    <meta property="og:description" content="Order delicious meals online from {{ config('app.name') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ URL::current() }}">
    <meta property="twitter:title" content="{{ $title ?? config('app.name', 'RestoApp') }}">
    <meta property="twitter:description" content="Order delicious meals online from {{ config('app.name') }}">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <title>{{ $title ?? config('app.name', 'RestoApp') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        [x-cloak] {
            display: none;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('images/pattern.webp') }}') no-repeat center;
            background-size: contain;
            background-repeat: repeat;
            /* opacity: 0.07; */
            /* Make pattern more subtle */
            filter: brightness(0.6);
            /* Darken the pattern */
            z-index: -1;
        }

        .main-content {
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white */
            backdrop-filter: blur(8px);
            /* Smooth frosted effect */
            border-radius: 10px;
            padding: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        <style>@keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 1.5s infinite;
        }

        .category-btn.active {
            background-color: #f59e0b;
            color: white;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #f59e0b;
        }

        .receipt-print {
            font-family: 'Courier New', monospace;
        }

        .order-item:hover {
            background-color: #f8fafc;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScriptConfig
    @stack('scripts')

</head>

<body class="font-sans antialiased bg-white text-retro-blue relative">

    <div x-data="{ isOpen: false }" x-cloak>
        <x-header />

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
