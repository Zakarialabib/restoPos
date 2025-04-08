<div>
    <div class="mt-4 px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-bold mb-4 text-orange-600 flex items-center">
                <x-icons.bell class="w-6 h-6 mr-2" />
                Pending Orders
                <span
                    class="ml-2 bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $pendingOrders->count() }}</span>
            </h2>
            <div class="space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                @foreach ($pendingOrders as $order)
                    <div class="bg-orange-50 rounded-lg p-4 cursor-pointer hover:bg-orange-100 transition-colors"
                        wire:click="updateOrderStatus({{ $order->id }}, App\Enums\KitchenOrderStatus::InProgress)">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">#{{ $order->order_id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->priority->value === 'high' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($order->priority->value) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-center py-1">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span class="text-sm text-gray-600">{{ $item->notes }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- In Progress Orders -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-bold mb-4 text-blue-600 flex items-center">
                <x-icons.dashboard class="w-6 h-6 mr-2" />
                In Progress
                <span
                    class="ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $inProgressOrders->count() }}</span>
            </h2>
            <div class="space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                @foreach ($inProgressOrders as $order)
                    <div class="bg-blue-50 rounded-lg p-4 cursor-pointer hover:bg-blue-100 transition-colors"
                        wire:click="updateOrderStatus({{ $order->id }}, App\Enums\KitchenOrderStatus::Completed)">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">#{{ $order->order_id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->priority->value === 'high' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($order->priority->value) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-center py-1">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span class="text-sm text-gray-600">{{ $item->notes }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-bold mb-4 text-green-600 flex items-center">
                <x-icons.delivery class="w-6 h-6 mr-2" />
                Completed
                <span
                    class="ml-2 bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $completedOrders->count() }}</span>
            </h2>
            <div class="space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                @foreach ($completedOrders as $order)
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">#{{ $order->order_id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-center py-1">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Delayed Orders -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-bold mb-4 text-red-600 flex items-center">
                <x-icons.info-circle class="w-6 h-6 mr-2" />
                Delayed
                <span
                    class="ml-2 bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $delayedOrders->count() }}</span>
            </h2>
            <div class="space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                @foreach ($delayedOrders as $order)
                    <div class="bg-red-50 rounded-lg p-4 cursor-pointer hover:bg-red-100 transition-colors"
                        wire:click="updateOrderStatus({{ $order->id }}, App\Enums\KitchenOrderStatus::InProgress)">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">#{{ $order->order_id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                Delayed
                            </span>
                        </div>
                        <div class="mt-2">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-center py-1">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span class="text-sm text-gray-600">{{ $item->notes }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Notification Sound -->
    <audio id="notificationSound" class="hidden">
        <source src="/sounds/notification.mp3" type="audio/mpeg">
    </audio>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const notificationSound = document.getElementById('notificationSound');

            @this.on('notify', (data) => {
                notificationSound.play();
                // Show notification using browser API
                if (Notification.permission === 'granted') {
                    new Notification('Kitchen Order Update', {
                        body: data.message,
                        icon: '/images/logo.png'
                    });
                }
            });
        });

        // Request notification permission
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
    </script>
</div>
