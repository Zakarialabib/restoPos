@props([
    'isActive' => false, 
    'title' => '', 
    'collapsible' => false,
    'isRtl' => false
])

@php
    $baseClasses = 'flex items-center gap-3 transition-all duration-200 rounded-lg px-3 py-3';
    
    $isActiveClasses = $isActive
        ? 'text-white bg-indigo-600 hover:bg-indigo-700 shadow-md'
        : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white';
        
    $classes = $baseClasses . ' ' . $isActiveClasses;
    
    if ($collapsible) {
        $classes .= ' w-full justify-between';
    }
    
    // Enhanced icon classes with colors
    $iconClasses = 'flex-shrink-0 w-5 h-5 ' . ($isActive 
        ? 'text-white' 
        : 'text-indigo-500 group-hover:text-indigo-600 dark:text-indigo-400 dark:group-hover:text-white');
        
    $textClasses = 'text-sm font-medium transition-opacity duration-200';
    $chevronClasses = 'w-4 h-4 transition-transform duration-200';
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes . ' group']) }}>
        <div class="flex items-center gap-3">
            @if ($icon ?? false)
                <div class="transition-colors duration-200">
                    {{ $icon }}
                </div>
            @else
                <x-icons.empty-circle class="{{ $iconClasses }}" aria-hidden="true" />
            @endif

            <span 
                x-show="isSidebarOpen || isSidebarHovered"
                x-transition:enter="transition-opacity"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="{{ $textClasses }}"
            >
                {{ $title }}
            </span>
        </div>

        <div 
            x-show="isSidebarOpen || isSidebarHovered"
            x-transition:enter="transition-opacity"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="{ 'rotate-180': open }"
            class="transition-transform duration-200"
        >
            <x-icons.chevron-down class="{{ $chevronClasses }}" />
        </div>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes . ' group']) }}>
        <div class="flex items-center gap-3" :class="{ 'flex-row-reverse': isRtl }">
            @if ($icon ?? false)
                <div class="transition-colors duration-200">
                    {{ $icon }}
                </div>
            @else
                <x-icons.empty-circle class="{{ $iconClasses }}" aria-hidden="true" />
            @endif

            <span 
                x-show="isSidebarOpen || isSidebarHovered"
                x-transition:enter="transition-opacity"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="{{ $textClasses }}"
            >
                {{ $title }}
            </span>
        </div>
    </a>
@endif