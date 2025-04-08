@props(['title', 'breadcrumbs' => []])

<div class="bg-orange-50 border-b border-orange-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <!-- Title -->
        <h2 class="w-full md:w-auto text-left text-2xl font-bold text-orange-800 mb-2 md:mb-0">
            {{ $title }}
        </h2>

        <!-- Breadcrumbs -->
        <nav class="flex items-center text-sm">
            <a href="/" class="flex items-center text-orange-600 hover:text-orange-800 no-underline transition">
                <i class="fas fa-home mr-2"></i>
                <span>Dashboard</span>
            </a>

            @foreach ($breadcrumbs as $breadcrumb)
                <span class="mx-2 text-orange-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>

                @if (!$loop->last)
                    <a href="{{ $breadcrumb['url'] }}"
                        class="flex items-center text-orange-600 hover:text-orange-800 no-underline transition">
                        @if (isset($breadcrumb['icon']))
                            <i class="{{ $breadcrumb['icon'] }} mr-2"></i>
                        @endif
                        <span>{{ $breadcrumb['name'] }}</span>
                    </a>
                @else
                    <span class="flex items-center text-orange-800 font-medium">
                        @if (isset($breadcrumb['icon']))
                            <i class="{{ $breadcrumb['icon'] }} mr-2"></i>
                        @endif
                        <span>{{ $breadcrumb['name'] }}</span>
                    </span>
                @endif
            @endforeach
        </nav>
    </div>
</div>
