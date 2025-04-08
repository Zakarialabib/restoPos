<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Digital Menu Display' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
        }

        .swiper-pagination-bullet-active {
            background: #fff;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.3);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px;
        }

        .tv-menu-root {
            --swiper-theme-color: var(--accent-color, #f97316);
            /* Use accent color for Swiper UI */
            --swiper-navigation-color: var(--text-color, #ffffff);
            --swiper-pagination-color: var(--accent-color, #f97316);
            --swiper-pagination-bullet-inactive-color: var(--text-color, #ffffff);
            --swiper-pagination-bullet-inactive-opacity: 0.3;
            --swiper-navigation-size: 30px;
            /* Smaller navigation arrows */
        }

        .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: calc(var(--swiper-navigation-size) / 44 * 27);
            height: var(--swiper-navigation-size);
            margin-top: calc(0px - (var(--swiper-navigation-size) / 2));
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            padding: 5px;
            transition: background-color 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background-color: rgba(0, 0, 0, 0.4);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: calc(var(--swiper-navigation-size) * 0.6);
            /* Adjust arrow size */
        }

        /* Mode Specific Root Styles (Example) */
        .mode-list-view .swiper-mode-container {
            /* Add padding if needed for vertical list */
        }

        /* Variant Specific Styles (Example) */
        .variant-paper .category-title {
            font-family: 'Merriweather', serif;
            /* Ensure fonts are loaded */
            border-bottom: 2px solid var(--primary-color);
        }

        .variant-paper .product-item {
            font-family: 'Lato', sans-serif;
            /* Ensure fonts are loaded */
        }

        .variant-tv .product-card-base {
            /* Base class added in partials */
            /* Example TV style */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .variant-tvMinimal .product-card-base {
            border: 1px solid var(--text-color-alpha);
        }

        /* Ken Burns Effect (Apply this class in Showcase partial) */
        .ken-burns-image {
            animation: kenburns 15s ease-out forwards infinite;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .scrollbar-thumb-orange-500::-webkit-scrollbar-thumb {
            background-color: var(--accent-color, #F97316);
            /* Use accent color or default orange */
            border-radius: 10px;
        }

        .scrollbar-track-gray-200::-webkit-scrollbar-track {
            background-color: #E5E7EB;
            /* Light gray track */
            border-radius: 10px;
        }

        @keyframes kenburns {
            0% {
                transform: scale(1.0) translate(0, 0);
            }

            50% {
                transform: scale(1.15) translate(5%, -5%);
            }

            100% {
                transform: scale(1.0) translate(0, 0);
            }
        }

        /* Ensure Alpine's x-cloak hides elements before init */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body class="antialiased">
    {{ $slot }}

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
