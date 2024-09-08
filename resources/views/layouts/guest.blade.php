<!DOCTYPE html>
<html x-data="mainTheme" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ isRtl() ? 'rtl' : 'ltr' }}"
    class="scroll-smooth">

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

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>{{ $title ?? '' }} | {{ config('app.name', 'RestoApp') }} </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @stack('styles')

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScriptConfig

    @stack('scripts')

</head>


<body class="bg-retro-cream text-retro-blue">
    {{-- <x-header /> --}}
    <main class="min-h-screen w-full p-0">
        {{ $slot }}
    </main>
</body>

</html>
