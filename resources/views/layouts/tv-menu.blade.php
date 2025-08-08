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

    @stack('styles')
    <style>
        :root {
            {!! app(\App\Services\ThemeService::class)->getCssVariables() !!}
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScriptConfig
    @stack('scripts')

</head>

<body class="font-sans antialiased bg-white text-retro-blue relative">
    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>
</html>
