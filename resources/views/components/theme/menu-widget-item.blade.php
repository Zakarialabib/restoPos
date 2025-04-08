{{-- resources/views/components/x-menu-widget.blade.php --}}
<div class="menu-widget-item">
    <div class="drag-handle cursor-move">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
        </svg>
    </div>
    <div class="menu-widget-content">
        {{ $slot }}
    </div>
</div>
