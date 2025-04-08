<div>
    <!-- Header and Search -->
    <x-theme.breadcrumb :title="__('Recipe Management')" :parent="route('admin.recipes')" :parentName="__('Recipe Management')">
        <div class="flex space-x-4">
            <x-input wire:model.live.debounce.300ms="searchTerm" placeholder="{{ __('Search recipes...') }}" class="w-64" />
            <x-button wire:click="createRecipe" color="primary">
                {{ __('Create Recipe') }}
            </x-button>
            <x-button wire:click="showPromotions" color="secondary">
                {{ __('Promotions') }}
            </x-button>
            <x-button wire:click="showReporting" color="secondary">
                {{ __('Reports') }}
            </x-button>
        </div>
    </x-theme.breadcrumb>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <x-label for="type">Type</x-label>
                <select wire:model.live="filters.type" class="w-full">
                    <option value="">All Types</option>
                    @foreach ($recipeTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-label for="status">Status</x-label>
                <select wire:model.live="filters.status" class="w-full">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div>
                <x-label for="cost_min">Min Cost</x-label>
                <x-input type="number" wire:model.live="filters.cost_min" class="w-full" step="0.01" />
            </div>
            <div>
                <x-label for="cost_max">Max Cost</x-label>
                <x-input type="number" wire:model.live="filters.cost_max" class="w-full" step="0.01" />
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <x-button type="button" wire:click="resetFilters">Reset Filters</x-button>
        </div>
    </div>

    <!-- Recipes Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recipes as $recipe)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $recipe?->name }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $recipe->type->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($recipe->estimated_cost, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleRecipeStatus({{ $recipe->id }})" type="button"
                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $recipe->status ? 'bg-green-500' : 'bg-gray-200' }}">
                                <span class="sr-only">Toggle status</span>
                                <span
                                    class="translate-x-0 pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $recipe->status ? 'translate-x-5' : 'translate-x-0' }}">
                                    <span
                                        class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity opacity-100 ease-in duration-200">
                                        <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                            <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <x-button type="button" wire:click="showOptimization({{ $recipe->id }})">
                                Optimize
                            </x-button>
                            <x-button type="button" wire:click="showSeasonalAlternatives({{ $recipe->id }})">
                                Seasonal
                            </x-button>
                            <x-button type="button" wire:click="duplicateRecipe({{ $recipe->id }})">
                                Duplicate
                            </x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No recipes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $recipes->links() }}
        </div>
    </div>

    <!-- Cost Optimization Modal -->
    <x-modal wire:model="showOptimizationModal" name="showOptimizationModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Optimization Suggestions</h3>
            @if ($recipe)

                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Recipe Cost Analysis</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Cost:</span>
                            <span class="font-bold">{{ number_format($recipe->calculateTotalCost(), 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Suggested Selling Price:</span>
                            <span
                                class="font-bold text-green-600">{{ number_format($recipe->calculateTotalCost() * 2.5, 2) }}
                                DH</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Profit Margin:</span>
                            <span class="font-bold text-blue-600">60%</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-2">Required Ingredients</h4>
                    <div class="space-y-2">
                        @foreach ($recipe->ingredients as $ingredient)
                            <div
                                class="flex justify-between items-center p-2 {{ $ingredient->stock_quantity >= $ingredient->pivot->quantity ? 'bg-green-50' : 'bg-red-50' }} rounded">
                                <div>
                                    <span class="font-medium">{{ $ingredient->name }}</span>
                                    <span class="text-sm text-gray-600">
                                        ({{ $ingredient->pivot->quantity }} {{ $ingredient->pivot->unit }})
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">In Stock:</span>
                                    <span
                                        class="{{ $ingredient->stock_quantity >= $ingredient->pivot->quantity ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $ingredient->stock_quantity }} {{ $ingredient->unit }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-modal>

    <!-- Seasonal Alternatives Modal -->
    <x-modal wire:model="showSeasonalAlternativesModal" name="showSeasonalAlternativesModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Seasonal Alternatives</h3>
            @if ($recipe)

                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">Seasonal Status</h4>
                    <div class="space-y-4">
                        @foreach ($recipe->ingredients as $ingredient)
                            @if ($ingredient->is_seasonal)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-blue-800">{{ $ingredient->name }}</span>
                                    @if ($ingredient->isInSeason())
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">In
                                            Season</span>
                                    @else
                                        <div class="text-sm text-red-600">
                                            Next Season: {{ $ingredient->getNextSeasonStart()?->format('M Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-modal>

    <!-- Promotions Modal -->
    <x-modal wire:model="showPromotionsModal" name="showPromotionsModal">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Promotional Combos</h3>

            <!-- Happy Hour Section -->
            <div class="mb-8 bg-gradient-to-r from-orange-100 to-yellow-100 rounded-lg p-4">
                <h4 class="text-lg font-semibold text-orange-800 mb-2">Happy Hour Deals</h4>
                @if ($happyHourDeals['active'])
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <span
                                class="text-lg font-bold text-orange-600">{{ $happyHourDeals['discount_percentage'] }}%
                                OFF</span>
                            <span class="text-sm text-gray-600">Ends
                                {{ $happyHourDeals['remaining_time'] }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($happyHourDeals['products'] as $product)
                                <div class="bg-orange-50 rounded p-3">
                                    <div class="font-medium">{{ $product['name'] }}</div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span
                                            class="text-gray-500 line-through">{{ number_format($product['original_price'], 2) }}
                                            DH</span>
                                        <span
                                            class="text-orange-600 font-bold">{{ number_format($product['happy_hour_price'], 2) }}
                                            DH</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-600">Next Happy Hour starts
                            {{ $happyHourDeals['next_happy_hour']->diffForHumans() }}</p>
                    </div>
                @endif
            </div>

            <!-- Promotional Combos -->
            <div class="grid grid-cols-2 gap-6">
                @foreach ($promotionalCombos as $combo)
                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                        <h4 class="font-semibold text-lg mb-2">{{ $combo['name'] }}</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Original Price:</span>
                                <span class="line-through">{{ number_format($combo['original_price'], 2) }}
                                    DH</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Combo Price:</span>
                                <span class="font-bold text-green-600">{{ number_format($combo['combo_price'], 2) }}
                                    DH</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">You Save:</span>
                                <span class="font-bold text-red-600">{{ number_format($combo['savings'], 2) }}
                                    DH</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-modal>

    <!-- Reporting Modal -->
    <x-modal wire:model="showReportingModal" name="showReportingModal">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Performance Reports</h3>
                <div class="flex space-x-4">
                    <x-input type="date" wire:model.live="dateRange.start" class="w-40" />
                    <x-input type="date" wire:model.live="dateRange.end" class="w-40" />
                </div>
            </div>
        </div>
    </x-modal>
</div>
