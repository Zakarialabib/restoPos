<x-sidebar.overlay />

<aside
    class="fixed inset-y-0 left-0 z-20 flex flex-col bg-gradient-to-b from-orange-500 to-orange-600 border-r border-orange-400 shadow-lg"
    :class="{
        'w-64': isSidebarOpen || isSidebarHovered,
        'w-20': !isSidebarOpen && !isSidebarHovered,
        'translate-x-0': isSidebarOpen || isSidebarHovered || window.innerWidth >= 1024,
        '-translate-x-full': !isSidebarOpen && !isSidebarHovered && window.innerWidth < 1024,
    }"
    style="transition-property: width, transform; transition-duration: 150ms;" @mouseenter="handleSidebarHover(true)"
    @mouseleave="handleSidebarHover(false)" x-transition:enter="transform transition-transform duration-200"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition-transform duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" x-cloak>

    <x-sidebar.header />

    <x-sidebar.content />

    <x-sidebar.footer />
</aside>
