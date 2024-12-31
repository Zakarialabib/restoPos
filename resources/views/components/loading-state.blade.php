@props(['type' => 'spinner', 'size' => 'md'])

<div
    x-data="{ show: false }"
    x-show="show"
    x-init="@if(isset($attributes['wire:loading']))
        $wire.on('loading.{{ $attributes['wire:loading']->value }}', () => show = true)
        $wire.on('loaded.{{ $attributes['wire:loading']->value }}', () => show = false)
    @endif"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    <div class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex items-center justify-center rounded-lg">
        <div class="flex flex-col items-center gap-2">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            @if(isset($slot) && $slot->isNotEmpty())
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $slot }}
                </span>
            @endif
        </div>
    </div>
</div> 