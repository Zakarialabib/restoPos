<div>
    <div class="min-h-screen bg-gray-100 p-4">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kitchen Display</h1>
                <p class="text-sm text-gray-600">Active Orders: {{ $activeOrders->count() }}</p>
            </div>

            <!-- Category Filter -->
            <div class="flex items-center space-x-4">
                <select wire:model.live="selectedCategory" class="form-select rounded-md shadow-sm">
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <!-- Auto-refresh Toggle -->
                <div x-data="{ autoRefresh: true }" class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Auto-refresh</span>
                    <button
                        @click="
                        autoRefresh = !autoRefresh;
                        if (autoRefresh) {
                            window.refreshInterval = setInterval(() => $wire.loadOrders(), {{ $refreshInterval * 1000 }});
                        } else {
                            clearInterval(window.refreshInterval);
                        }
                    "
                        :class="{ 'bg-green-500': autoRefresh, 'bg-gray-300': !autoRefresh }"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out">
                        <span :class="{ 'translate-x-5': autoRefresh, 'translate-x-0': !autoRefresh }"
                            class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Active Orders -->
            @foreach ($activeOrders as $order)
                <div wire:key="order-{{ $order->id }}" class="bg-white rounded-lg shadow-md overflow-hidden"
                    :class="{ 'border-l-4 border-red-500': {{ $order->priority }} }">
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                        <div>
                            <span class="font-semibold">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-600">
                                {{ $order->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Estimated Time -->
                            <span
                                class="text-sm font-medium px-2 py-1 rounded-full {{ $this->getEstimatedWaitTime($order) > 15 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $this->getEstimatedWaitTime($order) }}min
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-4">
                        <ul class="space-y-3">
                            @foreach ($order->items as $item)
                                <li class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium">{{ $item->quantity }}x</span>
                                        <span>{{ $item->product->name }}</span>
                                        @if ($item->notes)
                                            <p class="text-sm text-gray-600">{{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <button wire:click="markItemComplete({{ $item->id }})"
                                            class="text-sm px-2 py-1 rounded-full hover:bg-gray-100">
                                            Done
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Order Actions -->
                    <div class="bg-gray-50 px-4 py-3 border-t flex justify-between">
                        <button wire:click="markAsStarted({{ $order->id }})"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Start
                        </button>
                        <button wire:click="markAsCompleted({{ $order->id }})"
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            Complete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Completed Orders -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Recently Completed</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time Taken</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($completedOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->items->count() }} items
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->completed_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->completed_at->diffInMinutes($order->created_at) }}min
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notification Sound -->
        <audio id="notificationSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>

        <!-- Alpine.js Initialization -->
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Initialize auto-refresh
                window.refreshInterval = setInterval(() => {
                    @this.loadOrders()
                }, {{ $refreshInterval * 1000 }});

                // Handle notifications
                Livewire.on('playNotification', message => {
                    const audio = document.getElementById('notificationSound');
                    audio.play();

                    // Show browser notification if permitted
                    if (Notification.permission === 'granted') {
                        new Notification('Kitchen Display System', {
                            body: message,
                            icon: '/icon.png'
                        });
                    }
                });
            });
        </script>
    </div>
</div>
