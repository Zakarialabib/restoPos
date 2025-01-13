<div>
    <div x-data="{
        showForm: @entangle('showForm').live,
        expandedSections: {
            alerts: false,
            analytics: false
        },
        toggleSection(section) {
            this.expandedSections[section] = !this.expandedSections[section]
        }
    }" class="mx-auto p-4">
        @if (session()->has('success'))
            <x-alert type="success" :dismissal="false" :showIcon="true">
                {{ session('success') }}
            </x-alert>
        @endif

        @if (session()->has('error'))
            <x-alert type="error" :dismissal="false" :showIcon="true">
                {{ session('error') }}
            </x-alert>
        @endif
        <!-- Header Section -->
        <div class="bg-gray-50  rounded-lg shadow-lg py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                <h2 class="text-3xl font-bold mb-4 md:mb-0 text-black">{{ __('Ingredient Management') }}</h2>
                <div class="flex flex-row items-center gap-x-2">
                    <x-button x-on:click="showForm = true" primary size="xs" type="button">
                        <span class="material-icons text-xl">add</span>
                        <span>{{ __('Add Ingredient') }}</span>
                    </x-button>
                    @if ($selectAll && count($selectedIngredients) > 0)
                        <x-button wire:click="$set('showBulkActions', true)" size="xs" type="button" info>
                            <span class="material-icons text-sm">filter_list</span>
                            <span>{{ __('Bulk Actions') }}</span>
                        </x-button>
                    @endif
                </div>
            </div>

            <!-- Compact Filters -->
            <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl shadow-inner px-4 py-2">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <x-input wire:model.live="search" type="search" placeholder="{{ __('Search ingredients...') }}"
                        class="w-full  bg-white bg-opacity-50 border-0 placeholder-gray-500 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400" />

                    <select wire:model.live="category_filter"
                        class="bg-white bg-opacity-50 border-0 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select wire:change="filterByCategory($event.target.value)"
                        class="bg-white bg-opacity-50 border-0 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach ($availableCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="status_filter"
                        class="bg-white bg-opacity-50 border-0 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                        <option value="low_stock">{{ __('Low Stock') }}</option>
                        <option value="out_of_stock">{{ __('Out of Stock') }}</option>
                    </select>
                    <div class="flex space-x-2">
                        <x-input wire:model.live="startDate" type="date" placeholder="{{ __('Start Date') }}"
                            class="w-full bg-white bg-opacity-50 border-0 placeholder-gray-500 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400" />
                        <x-input wire:model.live="endDate" type="date" placeholder="{{ __('End Date') }}"
                            class="w-full bg-white bg-opacity-50 border-0 placeholder-gray-500 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div>
                        <select wire:model.live="selectedAnalyticsPeriod"
                            class="bg-white bg-opacity-50 border-0 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400">
                            <option value="7">{{ __('Last 7 Days') }}</option>
                            <option value="30">{{ __('Last 30 Days') }}</option>
                            <option value="90">{{ __('Last 90 Days') }}</option>
                            <option value="365">{{ __('Last Year') }}</option>
                        </select>
                        <select wire:model.live="selectedAnalyticsType"
                            class="bg-white bg-opacity-50 border-0 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400">
                            <option value="cost">{{ __('Cost Analytics') }}</option>
                            <option value="wastage">{{ __('Wastage Analytics') }}</option>
                            <option value="turnover">{{ __('Turnover Analytics') }}</option>
                            <option value="seasonality">{{ __('Seasonality Analytics') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    @if (count($selectedIngredients) > 0)
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-white">{{ count($selectedIngredients) }}
                                {{ __('selected') }}</span>

                        </div>
                    @endif
                </div>
            </div>
            <!-- Analytics Section -->

            <h2 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Analytics Overview') }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Total Ingredients -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Total Ingredients') }}</h3>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $this->ingredientAnalytics['total_ingredients'] }}
                    </p>
                </div>

                <!-- Active Ingredients -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Active Ingredients') }}</h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $this->ingredientAnalytics['active_ingredients'] }}
                    </p>
                </div>

                <!-- Low Stock -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Low Stock') }}</h3>
                    <p class="text-2xl font-bold text-red-600">
                        {{ $this->ingredientAnalytics['low_stock_count'] }}
                    </p>
                </div>

                <!-- Average Daily Usage -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Average Daily Usage') }}</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        {{-- {{ number_format($this->ingredientAnalytics['usage_trends']['average_daily_usage'], 2) }} --}}
                    </p>
                </div>

                <!-- Expiring Soon -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Expiring Soon') }}</h3>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{-- {{ count($this->ingredientAnalytics['expiring_soon']) }} --}}
                    </p>
                </div>
                @if ($selectedAnalyticsType === 'usage' || $selectedAnalyticsType === 'all')
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Usage Analytics') }}</h3>
                        <ul class="space-y-1">
                            @forelse ($this->ingredientAnalytics['usage_trends']['most_used'] as $ingredient)
                                <li class="flex justify-between text-sm text-gray-700">
                                    <span>{{ $ingredient->name }}</span>
                                    <span>{{ number_format($ingredient->total_usage, 2) }}
                                        {{ $ingredient->unit ?? '' }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">{{ __('No usage data available') }}</li>
                            @endforelse
                        </ul>
                    </div>
                @endif

                @if ($selectedAnalyticsType === 'wastage' || $selectedAnalyticsType === 'all')
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Wastage Analytics') }}</h3>
                        <ul class="space-y-1">
                            @forelse ($this->ingredientAnalytics['wastage_stats']['ingredients_with_wastage'] as $ingredient)
                                <li class="flex justify-between text-sm text-red-600">
                                    <span>{{ $ingredient->name }}</span>
                                    <span>{{ number_format($ingredient->total_wastage, 2) }}
                                        {{ $ingredient->unit ?? '' }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">{{ __('No wastage data available') }}</li>
                            @endforelse
                        </ul>
                    </div>
                @endif
                <!-- Usage Analytics -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Most Used Ingredients') }}</h3>
                    <div class="space-y-2">
                        @forelse($this->ingredientAnalytics['most_used_ingredients'] as $ingredient)
                            <div class="flex justify-between text-sm">
                                <span>{{ $ingredient->name }}</span>
                                <span>{{ $ingredient->total_quantity_used }} {{ $ingredient->unit }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('No usage data available') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Wastage Analytics -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Wastage Analytics') }}</h3>
                    <p class="text-2xl font-bold text-red-600">
                        {{ $this->wastageAnalytics['total_wastage_cost'] }}
                    </p>
                    <p class="mt-1 text-sm text-red-500">
                        {{ __('Avg Wastage: :avg%', ['avg' => number_format($this->wastageAnalytics['average_wastage_percentage'], 1)]) }}
                    </p>
                    @if ($this->wastageAnalytics['highest_wastage'])
                        <p class="mt-2 text-xs text-gray-500">
                            {{ __('Highest Wastage: :name', ['name' => $this->wastageAnalytics['highest_wastage']['name']]) }}
                        </p>
                    @endif
                </div>

                <!-- Cost Analytics -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Cost Analytics') }}</h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $this->costAnalytics['total_ingredient_cost'] }}
                    </p>
                    <p class="mt-1 text-sm text-green-500">
                        {{ __('Avg Cost Change: :avg%', ['avg' => number_format($this->costAnalytics['average_cost_change'], 1)]) }}
                    </p>
                    <div class="mt-2 text-xs text-gray-500">
                        <p>{{ __(':inc Increasing, :dec Decreasing', [
                            'inc' => $this->costAnalytics['ingredients_with_increasing_cost'],
                            'dec' => $this->costAnalytics['ingredients_with_decreasing_cost'],
                        ]) }}
                        </p>
                    </div>
                </div>

                <!-- Turnover Analytics -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium mb-2">{{ __('Turnover Analytics') }}</h3>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ number_format($this->turnoverAnalytics['average_turnover_rate'], 2) }}
                    </p>
                    <div class="mt-2 text-xs space-y-1">
                        <p>{{ __(':count need reorder', ['count' => count($this->turnoverAnalytics['ingredients_needing_reorder'])]) }}
                        </p>
                        <p>{{ __(':count slow moving', ['count' => count($this->turnoverAnalytics['slow_moving_ingredients'])]) }}
                        </p>
                        <p>{{ __(':count fast moving', ['count' => count($this->turnoverAnalytics['fast_moving_ingredients'])]) }}
                        </p>
                    </div>
                </div>

                @if ($selectedAnalyticsType === 'usage' || $selectedAnalyticsType === 'all')
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">{{ __('Usage Analytics') }}</h4>
                        <div class="space-y-2">
                            @forelse ($this->ingredientAnalytics['usage_trends']['most_used'] as $ingredient)
                                <div class="flex justify-between items-center text-sm">
                                    <span>{{ $ingredient->name }}</span>
                                    <span>
                                        {{ number_format($ingredient->total_usage, 2) }}
                                        {{ $ingredient->unit ?? '' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">{{ __('No usage data available') }}</p>
                            @endforelse

                            <div class="text-xs text-gray-500 mt-2">
                                <p>{{ __('Average Daily Usage') }}:
                                    {{ number_format($this->ingredientAnalytics['usage_trends']['average_daily_usage'], 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($selectedAnalyticsType === 'cost' || $selectedAnalyticsType === 'all')
                    <!-- Cost Analytics -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-4 py-2 bg-gray-300 border-2 border-gray-500">
                            <h4 class="text-lg font-semibold text-white flex items-center">
                                <span class="material-icons mr-2">monetization_on</span>
                                {{ __('Cost Analytics') }}
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Total Cost') }}</span>
                                <span class="text-lg font-bold text-blue-600">
                                    {{ number_format($this->costAnalytics['total_ingredient_cost'], 2) }} DH
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Avg Cost Change') }}</span>
                                <span
                                    class="text-lg font-bold {{ $this->costAnalytics['average_cost_change'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                                    {{ number_format($this->costAnalytics['average_cost_change'], 2) }}%
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="text-center">
                                    <span class="block text-gray-600">{{ __('Increasing') }}</span>
                                    <span class="font-semibold text-red-500">
                                        {{ $this->costAnalytics['ingredients_with_increasing_cost'] }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-gray-600">{{ __('Decreasing') }}</span>
                                    <span class="font-semibold text-green-500">
                                        {{ $this->costAnalytics['ingredients_with_decreasing_cost'] }}
                                    </span>
                                </div>
                            </div>
                            <ul class="divide-y divide-gray-200">
                                @foreach ($this->costAnalytics['detailed_cost_analytics']->take(5) as $ingredient)
                                    <li class="py-3 flex justify-between items-center">
                                        <span class="text-gray-800">{{ $ingredient['name'] }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="font-semibold {{ $ingredient['cost_change'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($ingredient['cost_change_percentage'], 2) }}%
                                            </span>
                                            <span
                                                class="text-xs px-2 py-1 rounded {{ $ingredient['cost_change'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $ingredient['trend_direction'] }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($selectedAnalyticsType === 'wastage' || $selectedAnalyticsType === 'all')
                    <!-- Wastage Analytics -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-5 bg-gradient-to-r from-red-500 to-red-600">
                            <h4 class="text-lg font-semibold text-white flex items-center">
                                <span class="material-icons mr-2">delete_outline</span>
                                {{ __('Wastage Analytics') }}
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Total Wastage Cost') }}</span>
                                <span class="text-lg font-bold text-red-600">
                                    {{ number_format($this->wastageAnalytics['total_wastage_cost'], 2) }} DH
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Average Wastage') }}</span>
                                <span class="text-lg font-bold text-red-500">
                                    {{ number_format($this->wastageAnalytics['average_wastage_percentage'], 2) }}%
                                </span>
                            </div>
                            @if ($this->wastageAnalytics['highest_wastage'])
                                <div class="bg-red-50 p-3 rounded-lg">
                                    <p class="text-sm text-red-700">
                                        <span class="font-semibold">{{ __('Highest Wastage') }}:</span>
                                        {{ $this->wastageAnalytics['highest_wastage']['name'] }}
                                        ({{ number_format($this->wastageAnalytics['highest_wastage']['wastage_percentage'], 2) }}%)
                                    </p>
                                </div>
                            @endif
                            <ul class="divide-y divide-gray-200">
                                @foreach ($this->wastageAnalytics['ingredients_with_wastage']->take(5) as $ingredient)
                                    <li class="py-3 flex justify-between items-center">
                                        <span class="text-gray-800">{{ $ingredient['name'] }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-red-600">
                                                {{ number_format($ingredient['wastage_percentage'], 2) }}%
                                            </span>
                                            <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">
                                                {{ number_format($ingredient['wastage_cost'], 2) }} DH
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($selectedAnalyticsType === 'turnover' || $selectedAnalyticsType === 'all')
                    <!-- Turnover Analytics -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-5 bg-gradient-to-r from-green-500 to-green-600">
                            <h4 class="text-lg font-semibold text-white flex items-center">
                                <span class="material-icons mr-2">autorenew</span>
                                {{ __('Turnover Analytics') }}
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Avg Turnover Rate') }}</span>
                                <span class="text-lg font-bold text-green-600">
                                    {{ number_format($this->turnoverAnalytics['average_turnover_rate'], 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Need Reorder') }}</span>
                                <span class="text-lg font-bold text-yellow-500">
                                    {{ count($this->turnoverAnalytics['ingredients_needing_reorder']) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="text-center">
                                    <span class="block text-gray-600">{{ __('Slow Moving') }}</span>
                                    <span class="font-semibold text-orange-500">
                                        {{ count($this->turnoverAnalytics['slow_moving_ingredients']) }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-gray-600">{{ __('Fast Moving') }}</span>
                                    <span class="font-semibold text-green-500">
                                        {{ count($this->turnoverAnalytics['fast_moving_ingredients']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($selectedAnalyticsType === 'seasonality' || $selectedAnalyticsType === 'all')
                    <!-- Seasonality Analytics -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-5 bg-gradient-to-r from-purple-500 to-purple-600">
                            <h4 class="text-lg font-semibold text-white flex items-center">
                                <span class="material-icons mr-2">event</span>
                                {{ __('Seasonality Analytics') }}
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Seasonal Ingredients') }}</span>
                                <span class="text-lg font-bold text-purple-600">
                                    {{ $this->seasonalityAnalytics['seasonal_ingredients_count'] }}
                                </span>
                            </div>
                            @if (count($this->seasonalityAnalytics['peak_seasons']) > 0)
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <p class="text-sm font-semibold text-purple-700 mb-2">
                                        {{ __('Peak Seasons') }}:
                                    </p>
                                    @foreach (array_slice($this->seasonalityAnalytics['peak_seasons'], 0, 3) as $month => $ingredients)
                                        <p class="text-sm text-purple-600">
                                            {{ $month }}: <span
                                                class="font-medium">{{ $ingredients->first()['name'] }}</span>
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Ingredient Form -->
        @if ($showForm)
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">
                        {{ $editingIngredientId ? __('Edit Ingredient') : __('Add Ingredient') }}
                    </h3>
                    <x-button wire:click="$toggle('showForm')" color="secondary">
                        <span class="material-icons">close</span>
                    </x-button>
                </div>

                <form wire:submit="saveIngredient" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input id="name" wire:model="name" type="text" class="w-full" />
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="description" :value="__('Description')" />
                            <x-textarea id="description" wire:model="description" class="w-full" rows="3" />
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category_id" :value="__('Category')" />
                            <select id="category_id" wire:model="category_id" class="w-full form-select">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="cost" :value="__('Cost')" />
                            <x-input id="cost" wire:model="cost" type="number" step="0.01"
                                class="w-full" />
                            @error('cost')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="stock" :value="__('Stock')" />
                            <x-input id="stock" wire:model="stock" type="number" step="0.01"
                                class="w-full" />
                            @error('stock')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="reorder_point" :value="__('Reorder Point')" />
                            <x-input id="reorder_point" wire:model="reorder_point" type="number" step="0.01"
                                class="w-full" />
                            @error('reorder_point')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="unit" :value="__('Unit')" />
                            <select id="unit" wire:model="unit" class="w-full form-select">
                                <option value="g">{{ __('Grams (g)') }}</option>
                                <option value="ml">{{ __('Milliliters (ml)') }}</option>
                                <option value="kg">{{ __('Kilograms (kg)') }}</option>
                                <option value="l">{{ __('Liters (l)') }}</option>
                                <option value="pcs">{{ __('Pieces') }}</option>
                            </select>
                            @error('unit')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <div>
                            <x-label for="expiry_date" :value="__('Expiry Date')" />
                            <x-input id="expiry_date" wire:model="expiry_date" type="date" class="w-full" />
                            @error('expiry_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="storage_location" :value="__('Storage Location')" />
                            <x-input id="storage_location" wire:model="storage_location" type="text"
                                class="w-full" />
                            @error('storage_location')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="supplier_info" :value="__('Supplier Information')" />
                            <x-textarea id="supplier_info" wire:model="supplier_info" class="w-full"
                                rows="3" />
                            @error('supplier_info')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="image" :value="__('Image')" />
                            <input type="file" id="image" wire:model="image" class="w-full"
                                accept="image/*" />
                            @error('image')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @if ($image && !is_string($image))
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}"
                                        class="h-20 w-20 object-cover rounded" />
                                </div>
                            @elseif ($image && is_string($image))
                                <div class="mt-2">
                                    <img src="{{ Storage::url($image) }}" class="h-20 w-20 object-cover rounded" />
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="status" class="form-checkbox" />
                                <span class="ml-2">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-2 pt-4">
                            <x-button wire:click="$toggle('showForm')" color="secondary" type="button">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button type="submit" color="primary">
                                {{ $editingIngredientId ? __('Update Ingredient') : __('Create Ingredient') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        <!-- Ingredients Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model.live="selectAll"
                                        class="form-checkbox rounded text-primary-600">
                                </label>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="$set('sortField', 'name')">
                                {{ __('Name') }}
                                @if ($sortField === 'name')
                                    <span
                                        class="material-icons">sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}</span>
                                @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Category') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Type') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="$set('sortField', 'stock_quantity')">
                                {{ __('Stock') }}
                                @if ($sortField === 'stock_quantity')
                                    <span
                                        class="material-icons">sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}</span>
                                @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="$set('sortField', 'cost')">
                                {{ __('Cost') }}
                                @if ($sortField === 'cost')
                                    <span
                                        class="material-icons">sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}</span>
                                @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($this->ingredients as $ingredient)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedIngredients"
                                            value="{{ $ingredient->id }}"
                                            class="form-checkbox rounded text-primary-600">
                                    </label>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @if ($ingredient->image)
                                            <img src="{{ Storage::url($ingredient->image) }}"
                                                class="h-8 w-8 rounded-full object-cover mr-2">
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $ingredient->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($ingredient->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <x-dropdown>
                                        <x-slot name="trigger">
                                            <button class="flex items-center text-sm">
                                                {{ $ingredient->category->name }}
                                                <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            @foreach ($availableCategories as $category)
                                                <x-dropdown.item
                                                    wire:click="changeCategory('{{ $ingredient->id }}', '{{ $category->id }}')"
                                                    :active="$ingredient->category_id === $category->id">
                                                    {{ $category->name }}
                                                </x-dropdown.item>
                                            @endforeach
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                                <td class="px-6 py-4">
                                    <x-type-badge :type="$ingredient->type" />
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm {{ $ingredient->stock_quantity <= $ingredient->reorder_point ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ number_format($ingredient->stock_quantity, 2) }}
                                            {{ $ingredient->unit }}
                                        </span>
                                        @if ($ingredient->stock_quantity <= $ingredient->reorder_point)
                                            <span class="text-xs text-red-500">{{ __('Low Stock') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm text-gray-900">{{ number_format($ingredient->cost, 2) }}</span>
                                        @if ($ingredient->prices()->count() > 1)
                                            <button wire:click="priceHistoryModal({{ $ingredient->id }})"
                                                class="text-xs text-primary-600 hover:text-primary-800">
                                                {{ __('View History') }}
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="toggleStatus({{ $ingredient->id }})"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ingredient->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $ingredient->status ? __('Active') : __('Inactive') }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-2">
                                        <x-button wire:click="editIngredient('{{ $ingredient->id }}')"
                                            color="primary" size="sm">
                                            <span class="material-icons">edit</span>
                                        </x-button>
                                        <x-button wire:click="openStockHistory('{{ $ingredient->id }}')"
                                            color="secondary" size="sm">
                                            <span class="material-icons">history</span>
                                        </x-button>
                                        <x-button wire:click="deleteIngredient('{{ $ingredient->id }}')"
                                            wire:confirm="{{ __('Are you sure you want to delete this ingredient?') }}"
                                            color="danger" size="sm">
                                            <span class="material-icons">delete</span>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                    {{ __('No ingredients found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t">
                {{ $this->ingredients->links() }}
            </div>
        </div>

        <!-- Bulk Actions Modal -->
        @if ($showBulkActions)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click.away="$set('showBulkActions', false)">
                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Bulk Actions') }}</h3>
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">{{ count($selectedIngredients) }}
                            {{ __('Selected Ingredients') }}</h4>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <x-label for="bulkCategory" :value="__('Category')" />
                            <select id="bulkCategory" wire:model="bulkCategory" class="w-full form-select">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <x-button wire:click="$set('showBulkActions', false)" color="secondary" type="button"
                                size="xs">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button wire:click="bulkUpdateCategory" color="primary" type="button" size="xs">
                                {{ __('Update Category') }}
                            </x-button>
                            <x-button wire:click="bulkUpdateStatus(true)" color="success" type="button"
                                size="xs">
                                {{ __('Activate') }}
                            </x-button>
                            <x-button wire:click="bulkUpdateStatus(false)" color="danger" type="button"
                                size="xs">
                                {{ __('Deactivate') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stock History Modal -->
        @if ($showStockHistory)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-4xl w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Stock History') }}</h3>
                        <x-button wire:click="$toggle('showStockHistory')" color="secondary">
                            <span class="material-icons">close</span>
                        </x-button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Type') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Quantity') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Reason') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($selectedIngredient?->stockLogs ?? [] as $log)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->type === 'addition' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($log->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ number_format($log->quantity, 2) }} {{ $selectedIngredient->unit }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $log->reason }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                            {{ __('No stock history found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Price History Modal -->
        @if ($showPriceHistory)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-4xl w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Price History') }}</h3>
                        <x-button wire:click="$toggle('showPriceHistory')" color="secondary">
                            <span class="material-icons">close</span>
                        </x-button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Cost') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Change') }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Notes') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($selectedIngredient?->prices ?? [] as $price)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $price->effective_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ number_format($price->cost, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $price->cost_change > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ number_format($price->cost_change_percentage, 2) }}%
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $price->reason }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                            {{ __('No price history found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
