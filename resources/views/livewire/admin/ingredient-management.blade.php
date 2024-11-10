<div>
    <div class="w-full">
        <!-- Header with Title and Actions -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Ingredient Management') }}</h2>
                <p class="text-sm text-gray-600">{{ __('Manage your ingredient inventory') }}</p>
            </div>
            <x-button wire:click="$toggle('showForm')" color="primary">
                <span class="material-icons">{{ $showForm ? 'close' : 'add' }}</span>
                {{ $showForm ? __('Close Form') : __('Add Ingredient') }}
            </x-button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <x-input type="text" wire:model.live="search" class="w-full rounded border-gray-300"
                    placeholder="{{ __('Search ingredients...') }}" />
            </div>
            <select wire:model.live="category_id" class="rounded border-gray-300">
                <option value="">{{ __('All categories') }}</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Ingredient Form -->
        @if ($showForm)
            <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    {{ $editingId ? __('Edit Ingredient') : __('Add New Ingredient') }}
                </h3>

                <form wire:submit="saveIngredient">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column - Basic Info -->
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-input type="text" wire:model="name" id="name" class="w-full"
                                    placeholder="{{ __('Enter ingredient name') }}" />
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <x-input-label for="categoryId" :value="__('Category')" />
                                <select wire:model="categoryId" id="categoryId" class="w-full rounded border-gray-300">
                                    <option value="">{{ __('Select category') }}</option>
                                    @foreach ($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <x-input-label for="expiryDate" :value="__('Expiry Date')" />
                                <x-input type="date" wire:model="expiryDate" id="expiryDate" class="w-full" />
                                @error('expiryDate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column - Stock Management -->
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="stock" :value="__('Stock Amount')" />
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <x-input type="number" wire:model="stock" id="stock" class="w-full"
                                            step="0.01" placeholder="0.00" />
                                        @error('stock')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-1/3">
                                        <select wire:model="unit" id="unit" class="w-full rounded border-gray-300">
                                            <option value="">{{ __('Unit') }}</option>
                                            @foreach ($this->units as $unit)
                                                <option @selected($unit->value === $this->unit) value="{{ $unit->value }}">
                                                    {{ $unit->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="conversionRate" :value="__('Conversion Rate')" />
                                <x-input type="number" wire:model="conversionRate" id="conversionRate" class="w-full"
                                    step="0.01" placeholder="1.00" />
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ __('Rate for converting between different units') }}
                                </p>
                                @error('conversionRate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Add this section inside the form, after the Right Column -->
                    <div class="col-span-2 space-y-6 mt-6 border-t pt-6">
                        <h4 class="font-semibold text-lg">{{ __('Additional Information') }}</h4>

                        <!-- Cost and Price -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="cost" :value="__('Cost')" />
                                <x-input type="number" wire:model="cost" id="cost" class="w-full" step="0.01"
                                    placeholder="0.00" />
                                @error('cost')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-input type="number" wire:model="price" id="price" class="w-full" step="0.01"
                                    placeholder="0.00" />
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nutritional Information -->
                        <div>
                            <x-input-label :value="__('Nutritional Information (per 100g)')" />
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                <div>
                                    <x-input type="number" wire:model="nutritionalInfo.calories" placeholder="Calories"
                                        class="w-full" step="0.1" />
                                    <span class="text-sm text-gray-500">{{ __('Calories') }}</span>
                                </div>
                                <div>
                                    <x-input type="number" wire:model="nutritionalInfo.protein" placeholder="Protein"
                                        class="w-full" step="0.1" />
                                    <span class="text-sm text-gray-500">{{ __('Protein (g)') }}</span>
                                </div>
                                <div>
                                    <x-input type="number" wire:model="nutritionalInfo.carbs" placeholder="Carbs"
                                        class="w-full" step="0.1" />
                                    <span class="text-sm text-gray-500">{{ __('Carbs (g)') }}</span>
                                </div>
                                <div>
                                    <x-input type="number" wire:model="nutritionalInfo.fat" placeholder="Fat"
                                        class="w-full" step="0.1" />
                                    <span class="text-sm text-gray-500">{{ __('Fat (g)') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex justify-end gap-4">
                        <x-button type="button" wire:click="$set('showForm', false)" color="secondary">
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button type="submit" color="primary">
                            {{ $editingId ? __('Update Ingredient') : __('Create Ingredient') }}
                        </x-button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Ingredients List -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Stock') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Cost/Price') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->ingredients as $ingredient)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $ingredient->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $ingredient->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $ingredient->stock }} {{ $ingredient->unit->label() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-green-100 text-green-800' => $ingredient->stock > 0,
                                        'bg-red-100 text-red-800' => $ingredient->stock <= 0,
                                    ])>
                                        {{ $ingredient->stock > 0 ? __('In Stock') : __('Out of Stock') }}
                                    </span>
                                    @if ($ingredient->expiry_date)
                                        <span @class([
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-yellow-100 text-yellow-800' => $ingredient->expiry_date->isPast(),
                                            'bg-blue-100 text-blue-800' => !$ingredient->expiry_date->isPast(),
                                        ])>
                                            {{ __('Expires:') }} {{ $ingredient->expiry_date->format('Y-m-d') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div>{{ __('Cost:') }} {{ $ingredient->cost }}</div>
                                    <div>{{ __('Price:') }} {{ $ingredient->price }}</div>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-green-100 text-green-800' => !$ingredient->isLowStock(),
                                    'bg-red-100 text-red-800' => $ingredient->isLowStock(),
                                ])>
                                    {{ $ingredient->isLowStock() ? __('Low Stock') : __('In Stock') }}
                                </span>
                            </td> --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <x-button wire:click="editIngredient({{ $ingredient->id }})" color="info"
                                        size="xs">
                                        <span class="material-icons">edit</span>
                                    </x-button>
                                    <x-button wire:click="deleteIngredient({{ $ingredient->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this ingredient?') }}"
                                        color="danger" size="xs">
                                        <span class="material-icons">delete</span>
                                    </x-button>
                                    <x-button wire:click="showPriceHistoryModal({{ $ingredient->id }})"
                                        color="secondary" size="xs">
                                        <span class="material-icons">history</span>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                {{ __('No ingredients found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

        <div class="mt-4">
            {{ $this->ingredients->links() }}
        </div>

        <!-- Price History Modal -->
        @if ($showPriceHistory)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="priceHistoryModal">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Price History') }}</h3>
                        <button wire:click="$set('showPriceHistory', false)" class="text-gray-500">
                            <span class="material-icons">close</span>
                        </button>
                    </div>

                    <!-- Add New Price Form -->
                    <div class="mb-4 p-4 bg-gray-50 rounded">
                        <h4 class="font-medium mb-2">{{ __('Add New Price') }}</h4>
                        <div class="space-y-3">
                            <div>
                                <x-input-label for="newCost" :value="__('Cost')" />
                                <x-input type="number" wire:model="newPrice.cost" id="newCost" class="w-full"
                                    step="0.01" />
                            </div>
                            <div>
                                <x-input-label for="newPrice" :value="__('Price')" />
                                <x-input type="number" wire:model="newPrice.price" id="newPrice" class="w-full"
                                    step="0.01" />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Notes')" />
                                <x-input type="text" wire:model="newPrice.notes" id="notes" class="w-full" />
                            </div>
                            <x-button wire:click="addNewPrice({{ $selectedIngredientId }})" color="primary"
                                class="w-full">
                                {{ __('Add Price') }}
                            </x-button>
                        </div>
                    </div>

                    <!-- Price History Table -->
                    <div class="overflow-y-auto max-h-60">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-xs text-gray-500">{{ __('Date') }}</th>
                                    <th class="px-4 py-2 text-xs text-gray-500">{{ __('Cost') }}</th>
                                    <th class="px-4 py-2 text-xs text-gray-500">{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($this->priceHistory as $price)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $price['date'] }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $price['cost'] }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $price['price'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
