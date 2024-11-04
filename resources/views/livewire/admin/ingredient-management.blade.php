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
                                <x-input type="number" wire:model="conversionRate" id="conversionRate" 
                                    class="w-full" step="0.01" placeholder="1.00" />
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ __('Rate for converting between different units') }}
                                </p>
                                @error('conversionRate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
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
                                    <x-button wire:click="editIngredient({{ $ingredient->id }})" color="info" size="xs">
                                        <span class="material-icons">edit</span>
                                    </x-button>
                                    <x-button wire:click="deleteIngredient({{ $ingredient->id }})" 
                                        wire:confirm="{{ __('Are you sure you want to delete this ingredient?') }}"
                                        color="danger" size="xs">
                                        <span class="material-icons">delete</span>
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
    </div>
</div>
