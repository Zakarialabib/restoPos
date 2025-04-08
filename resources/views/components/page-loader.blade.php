@props(['showText' => true])
<div x-data="pageLoader" x-show="!pageLoaded" class="fixed inset-0 z-[100] flex items-center justify-center"
    :class="{ 'opacity-0 pointer-events-none': !isLoading }" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div class="relative w-64">
        <!-- Progress Bar -->
        <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-accent-600 transition-all duration-300 ease-out" :style="{ width: `${progress}%` }">
            </div>
        </div>

        <!-- Loading Text -->
        @if ($showText)
            <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400" :class="{ 'opacity-0': !showText }"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                Loading...
            </div>
        @endif

        <!-- Loading Animation -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-8 h-8 border-4 border-accent-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</div>
