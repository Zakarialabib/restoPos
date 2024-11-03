<div>
    <div class="w-full py-12">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach ($this->inventoryStats as $key => $value)
                <x-card-tooltip :title="__(Str::title(Str::snake($key, ' ')))" :color="$key === 'low_stock_count' ? 'red' : 'blue'"
                    icon="{{ $key === 'total_products' ? 'box' : ($key === 'low_stock_count' ? 'exclamation' : 'clock') }}">
                    <span class="text-2xl font-bold">{{ $value }}</span>
                </x-card-tooltip>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Low Stock Ingredients -->
            <x-card header="{{ __('Low Stock Ingredients') }}">
                <div class="space-y-4">
                    @forelse($this->lowStockIngredients as $ingredient)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold">{{ $ingredient->name }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ __('Type') }}: {{ ucfirst($ingredient->type) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <x-alert type="info" show-icon>
                            {{ __('No low stock ingredients found.') }}
                        </x-alert>
                    @endforelse
                </div>
            </x-card>

            <!-- Recent Alerts -->
            <div class="lg:col-span-2">
                <x-card header="{{ __('Recent Alerts') }}">
                    <div class="space-y-4">
                        @forelse($this->recentAlerts as $alert)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <x-badge :color="$alert->is_resolved ? 'success' : 'warning'"
                                            icon="{{ $alert->is_resolved ? 'check' : 'bell' }}" />
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $alert->message }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $alert->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                @unless ($alert->is_resolved)
                                    <x-button wire:click="resolveAlert({{ $alert->id }})" color="success" size="sm"
                                        icon="check">
                                        {{ __('Resolve') }}
                                    </x-button>
                                @endunless
                            </div>
                        @empty
                            <x-alert type="info" show-icon>
                                {{ __('No recent alerts.') }}
                            </x-alert>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Sales Chart -->
            <div class="lg:col-span-2">
                <x-card header="{{ __('Sales Overview') }}">
                    <div class="flex items-center justify-end mb-4">
                        <x-button.group>
                            @foreach (['today', 'week', 'month', 'year'] as $range)
                                <x-button wire:click="setDateRange('{{ $range }}')" :color="$dateRange === $range ? 'primary' : 'secondary'"
                                    size="sm">
                                    {{ __(Str::title($range)) }}
                                </x-button>
                            @endforeach
                        </x-button.group>
                    </div>
                    <div class="h-80">
                        <x-apex-charts :chart-id="'sales-chart'" :categories="$this->salesChartData['categories']" :series-name="__('Sales')" :series-data="$this->salesChartData['data']" />
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>
