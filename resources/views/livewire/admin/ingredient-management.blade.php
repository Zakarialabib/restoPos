<div>
    <div class="w-full py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">{{ __('Ingredient Management') }}</h2>
            <button wire:click="$toggle('showForm')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ $showForm ? __('Cancel') : __('Add Ingredient') }}
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" class="w-full rounded border-gray-300"
                    placeholder="{{ __('Search ingredients...') }}">
            </div>
            <select wire:model.live="category_id" class="rounded border-gray-300">
                <option value="">{{ __('All categoreies') }}</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Form -->
        @if ($showForm)
            <form wire:submit="saveIngredient" class="mb-6 bg-gray-50 p-4 rounded">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Basic Info -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-input type="text" wire:model="name" id="name" class="w-full" />
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Stock Management -->
                    <div>
                        <x-input-label for="stock" :value="__('Stock')" />
                        <x-input type="number" wire:model="stock" id="stock" class="w-full" step="0.01" />
                        @error('stock')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Measurement -->
                    <div>
                        <x-input-label for="unit" :value="__('Unit')" />
                        <select wire:model="unit" id="unit" class="w-full rounded border-gray-300">
                            <option value="">{{ __('Select a unit') }}</option>
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

                    <div>
                        <x-input-label for="conversionRate" :value="__('Conversion Rate')" />
                        <x-input type="number" wire:model="conversionRate" id="conversionRate" class="w-full"
                            step="0.01" />
                        @error('conversionRate')
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

                    <div>
                        <x-input-label for="categoryId" :value="__('Category')" />
                        <select wire:model="categoryId" id="categoryId" class="w-full rounded border-gray-300">
                            <option value="">{{ __('All categoreies') }}</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-button type="submit" class="bg-blue-500 hover:bg-blue-600">
                            {{ $editingId ? __('Update Ingredient') : __('Add Ingredient') }}
                        </x-button>
                    </div>
                </div>
            </form>
        @endif

        <!-- Ingredients List -->
        <div class="overflow-x-auto">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="editIngredient({{ $ingredient->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    {{ __('Edit') }}
                                </button>
                                <button wire:click="deleteIngredient({{ $ingredient->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this ingredient?') }}"
                                    class="text-red-600 hover:text-red-900">
                                    {{ __('Delete') }}
                                </button>
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
