<?php

use Livewire\Volt\Component;
// use function Livewire\Volt\{title};
// title('Menu');
?>

<div>

    <x-sidebar.overlay />

    <aside
        class="fixed inset-y-0 left-0 z-20 flex flex-col bg-gray-800 border-r border-gray-700 shadow-lg"
        :class="{
            'translate-x-0 w-64': isSidebarOpen || isSidebarHovered,
            'w-16 translate-x-0 hidden lg:block': !isSidebarOpen && !isSidebarHovered,
            '-translate-x-full w-64 md:w-auto md:translate-x-0 ': !isSidebarOpen && !isSidebarHovered,
        }"
        style="transition-property: width, transform; transition-duration: 150ms;"
        @mouseenter="handleSidebarHover(true)"
        @mouseleave="handleSidebarHover(false)"
        x-transition:enter="transform transition-transform duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition-transform duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full">

        <x-sidebar.header />

        <x-sidebar.content />

        <x-sidebar.footer />
    </aside>

</div>