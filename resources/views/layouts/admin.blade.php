<!DOCTYPE html>

<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="mainTheme"
    dir="{{ isRtl() ? 'rtl' : 'ltr' }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="dns-prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="preconnect" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prerender" href="{{ request()->getSchemeAndHttpHost() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="noindex, nofollow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>{{ $title ?? '' }} - {{ config('app.name', 'RestoPos') }} </title>
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

    <!-- Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    @livewireScriptConfig

    @stack('scripts')

</head>

<body class="antialiased bg-body text-body font-body">
    <div @resize.window="handleWindowResize">
        <div class="min-h-screen">
            <!-- Page Loader -->

            <!-- Theme Customizer -->
            <livewire:theme-customizer />

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Page Wrapper -->
            <div class="flex flex-col min-h-screen transition-all duration-150"
                :class="{
                    'lg:ml-64': isSidebarOpen || isSidebarHovered,
                    'lg:ml-16': !isSidebarOpen && !isSidebarHovered,
                    'ml-0': window.innerWidth < 1024
                }">

                <!-- Page Content -->
                <main class="flex-1 flex-grow w-full mx-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>

</html>
