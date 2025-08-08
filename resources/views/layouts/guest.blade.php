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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            {!! app(\App\Services\ThemeService::class)->getCssVariables() !!}
        }
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
            background-size: cover;
            background-repeat: repeat;
            opacity: 0.03;
            z-index: -1;
        }

        .main-content {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
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

<body class="font-sans antialiased bg-gray-50 text-gray-800 relative">
    <div x-data="{ isOpen: false }">
        {{-- <x-alert /> --}}
        <x-header />

        {{-- Dynamic Sidebar Example --}}
        @php
            $sidebarItems = [
                [
                    'type' => 'header',
                    'title' => 'Main Navigation',
                ],
                [
                    'type' => 'dropdown',
                    'title' => 'Dashboard',
                    'icon' => 'dashboard',
                    'active' => request()->routeIs(['admin.dashboard', 'admin.kitchen.index']),
                    'items' => [
                        [
                            'type' => 'link',
                            'title' => 'Overview',
                            'route' => 'admin.dashboard',
                            'active' => request()->routeIs('admin.dashboard'),
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Kitchen',
                            'route' => 'admin.kitchen.dashboard',
                            'active' => request()->routeIs('admin.kitchen.dashboard'),
                        ],
                    ],
                ],
                [
                    'type' => 'divider',
                ],
                [
                    'type' => 'link',
                    'title' => 'Profile',
                    'route' => 'profile',
                    'icon' => 'person',
                    'active' => request()->routeIs('profile'),
                ],
                [
                    'type' => 'custom',
                    'view' => 'components.sidebar.custom-analytics',
                    'data' => ['value' => 42],
                ],
            ];
        @endphp
        <x-sidebar :items="$sidebarItems" />
        <!-- Main Content -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-linear-to-r from-orange-600 to-orange-500 text-white py-8">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-6 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white p-2 rounded-full shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold tracking-tight">{{ config('app.name') }}</span>
                        </div>
                        <p class="mt-2 text-sm text-orange-100">Delicious meals, delivered fresh to your door.</p>
                    </div>
                    <div class="flex flex-col md:flex-row gap-y-4 md:space-y-0 md:space-x-12">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ url('/') }}"
                                        class="text-orange-100 hover:text-white transition-colors duration-200">Home</a>
                                </li>
                                <li><a href="{{ url('compose/fruits') }}"
                                        class="text-orange-100 hover:text-white transition-colors duration-200">Compose
                                        Juice</a></li>
                                <li><a href="#"
                                        class="text-orange-100 hover:text-white transition-colors duration-200">About
                                        Us</a></li>
                                <li><a href="#"
                                        class="text-orange-100 hover:text-white transition-colors duration-200">Contact</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Contact Us</h3>
                            <ul class="space-y-2">
                                <li class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-orange-100">info@example.com</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-orange-100">+1 (555) 123-4567</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-t border-orange-400 mt-8 pt-6 text-center">
                    <p class="text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Theme Switcher -->
        {{-- <x-theme-switcher /> --}}

    </div>
</body>


</html>
