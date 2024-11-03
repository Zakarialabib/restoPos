<!-- Overlay -->
<div x-show="isSidebarOpen" @click="isSidebarOpen()" class="fixed inset-0 z-10 bg-gray-900/50 lg:hidden"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>
