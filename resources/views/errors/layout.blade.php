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

    <!-- Styles -->
    @livewireStyles

    @stack('styles')
    <!-- Scripts -->
    @vite(['resources/css/app.css'])

</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-2xl">
            <div class="text-center">
                <h1 class="text-9xl font-bold text-orange-500">@yield('code')</h1>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">@yield('message')</h2>
                <p class="mt-2 text-sm text-gray-600">@yield('submessage')</p>
            </div>
            <div class="mt-8">
                <a href="{{ url('/') }}"
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
