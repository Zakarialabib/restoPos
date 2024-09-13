<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">{{ __('Inventory Dashboard') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Low Stock Products -->
                    <div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Low Stock Products') }}</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <ul class="divide-y divide-gray-200">
                                @forelse($lowStockProducts as $product)
                                    <li class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>
                                            </div>
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Low Stock
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-3 text-sm text-gray-500">No low stock products</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Low Stock Ingredients -->
                    <div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Low Stock Ingredients') }}</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <ul class="divide-y divide-gray-200">
                                @forelse($lowStockIngredients as $ingredient)
                                    <li class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $ingredient->name }}</p>
                                                <p class="text-sm text-gray-500">Quantity: {{ $ingredient->quantity }}
                                                    {{ $ingredient->unit }}</p>
                                            </div>
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Reorder
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-3 text-sm text-gray-500">No low stock ingredients</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recent Inventory Alerts -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-2">{{ __('Recent Inventory Alerts') }}</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @forelse($recentAlerts as $alert)
                                <li class="px-4 py-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $alert->message }}</p>
                                            <p class="text-sm text-gray-500">{{ $alert->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <button wire:click="resolveAlert({{ $alert->id }})"
                                            class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                            Resolve
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-3 text-sm text-gray-500">No recent alerts</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
