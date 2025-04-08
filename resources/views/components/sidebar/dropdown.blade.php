@props(['title', 'active'])

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="w-full flex items-center justify-between px-2 py-2 bg-white text-orange-600 hover:bg-orange-800 rounded-lg transition-colors duration-200">
        <div class="flex items-center">
            @if(isset($icon))
                <div class="mr-3">
                    {{ $icon }}
                </div>
            @endif
            <span class="text-sm font-medium">{{ $title }}</span>
        </div>
        <span class="material-icons text-sm transform transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="mt-1 pl-4 space-y-1">
        {{ $slot }}
    </div>
</div>