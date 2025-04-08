<div>
    <div class="bg-white rounded-lg shadow" x-data="{ showFilters: false }">
        <!-- Stats Section -->
        <div class="px-4 py-5 sm:px-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $this->stats['pending_orders'] }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $this->stats['in_progress_orders'] }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Completed Today</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $this->stats['completed_today'] }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg. Prep Time</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ round($this->stats['average_preparation_time']) }}m</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Orders Section -->
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Kitchen Orders
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Manage and track kitchen orders
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button 
                        @click="showFilters = !showFilters"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Panel -->
        <div x-show="showFilters" class="border-t border-gray-200 px-4 py-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Status</label>
                    <select 
                        id="status-filter" 
                        wire:model.live="statusFilter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Statuses</option>
                        @foreach(\App\Enums\KitchenOrderStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority-filter" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select 
                        id="priority-filter" 
                        wire:model.live="priorityFilter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div>
                    <label for="assigned-filter" class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select 
                        id="assigned-filter" 
                        wire:model.live="assignedToFilter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Staff</option>
                        <!-- Add staff options dynamically -->
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="resetFilters"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Reset Filters
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="border-t border-gray-200">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($this->orders as $order)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                            @switch($order->status)
                                                @case(\App\Enums\KitchenOrderStatus::Pending)
                                                    <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    @break
                                                @case(\App\Enums\KitchenOrderStatus::InProgress)
                                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    @break
                                                @case(\App\Enums\KitchenOrderStatus::Completed)
                                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Order #{{ $order->reference }}
                                                <span class="font-medium text-gray-900">by {{ $order->customer_name }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $order->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @switch($order->priority)
                                                    @case('high')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @case('medium')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('low')
                                                        bg-green-100 text-green-800
                                                        @break
                                                @endswitch
                                            ">
                                                {{ ucfirst($order->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Order Items -->
                        <li class="ml-12 mb-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $item->orderItem->product->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->quantity }}x</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($item->status === \App\Enums\KitchenOrderStatus::Pending)
                                                    <button 
                                                        wire:click="updateItemStatus('{{ $item->id }}', '{{ \App\Enums\KitchenOrderStatus::InProgress->value }}')"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        Start
                                                    </button>
                                                @elseif($item->status === \App\Enums\KitchenOrderStatus::InProgress)
                                                    <button 
                                                        wire:click="updateItemStatus('{{ $item->id }}', '{{ \App\Enums\KitchenOrderStatus::Completed->value }}')"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                    >
                                                        Complete
                                                    </button>
                                                @endif
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @switch($item->status)
                                                        @case(\App\Enums\KitchenOrderStatus::Pending)
                                                            bg-yellow-100 text-yellow-800
                                                            @break
                                                        @case(\App\Enums\KitchenOrderStatus::InProgress)
                                                            bg-blue-100 text-blue-800
                                                            @break
                                                        @case(\App\Enums\KitchenOrderStatus::Completed)
                                                            bg-green-100 text-green-800
                                                            @break
                                                    @endswitch
                                                ">
                                                    {{ $item->status->label() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-4 text-gray-500">
                            No orders found
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="px-4 py-3 sm:px-6">
            {{ $this->orders->links() }}
        </div>
    </div>
</div> 