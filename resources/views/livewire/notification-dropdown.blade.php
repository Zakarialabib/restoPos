<div>
    <div class="relative" x-data="{ open: @entangle('showDropdown') }">
        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
            <span class="sr-only">View notifications</span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
            @if ($unreadCount > 0)
                <span
                    class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-red-500 text-xs text-white flex items-center justify-center">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                    @if ($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="text-sm text-blue-600 hover:text-blue-800">
                            Mark all as read
                        </button>
                    @endif
                </div>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <div class="flex items-start p-3 rounded-lg {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }}">
                            <div class="flex-shrink-0">
                                @switch($notification->type)
                                    @case('order')
                                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    @break

                                    @case('inventory')
                                        <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    @break

                                    @case('purchase')
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    @break

                                    @case('ingredient')
                                        <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @break

                                    @default
                                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if (!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')"
                                    class="ml-4 text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Mark as read</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            No notifications
                        </div>
                    @endforelse
                </div>

                {{-- @if ($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
</div>
