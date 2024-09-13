<div>
    <div class="py-12">

        <div class="flex flex-wrap mt-4 px-2">

            <form wire:submit="saveProduct" class="w-1/4">
                <x-accordion :title="$editingProductId ? 'Edit Product' : 'Add New Product'" open="true">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name" wire:model="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" id="price" wire:model="price"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('price')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category_id" wire:model="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select a category</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" id="stock" wire:model="stock"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('stock')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="text" id="image" wire:model="image"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('image')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" wire:model="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            @error('description')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_available" wire:model="is_available"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <label for="is_available" class="ml-2 block text-sm text-gray-900">Available</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_composable" wire:model="is_composable"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <label for="is_composable" class="ml-2 block text-sm text-gray-900">Composable</label>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ingredients</h3>
                            <div class="space-y-2">
                                @foreach ($this->ingredients as $ingredient)
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selectedIngredients.{{ $ingredient->id }}.id"
                                            value="{{ $ingredient->id }}" id="ingredient-{{ $ingredient->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <label for="ingredient-{{ $ingredient->id }}"
                                            class="text-sm text-gray-700">{{ $ingredient->name }}</label>
                                        <input type="number"
                                            wire:model="selectedIngredients.{{ $ingredient->id }}.quantity"
                                            placeholder="Quantity"
                                            class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm">
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedIngredients')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="flex items-center justify-end  px-4 mt-4">
                        @if ($editingProductId)
                            <x-button type="button" color="secondary" wire:click="cancelEdit">
                                <span class="material-icons">
                                    cancel
                                </span>
                            </x-button>
                        @endif
                        <x-button type="submit" color="primary">
                            {{ $editingProductId ? __('Update Product') : __('Add Product') }}
                        </x-button>
                    </div>
                </x-accordion>

            </form>
            <!-- Inventory Alerts -->
            {{-- <div class="mb-8">
                    <h3 class="text-xl font-bold mb-2">Inventory Alerts</h3>
                    @foreach ($inventoryAlerts as $alert)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-2">
                            <p>{{ $alert->message }}</p>
                            <button wire:click="resolveAlert({{ $alert->id }})" class="text-sm text-blue-500">Mark as
                                Resolved</button>
                        </div>
                    @endforeach
                </div> --}}

            <div class="w-3/4 ">
                <h2 class="text-2xl font-semibold mb-4 text-center">{{ __('Product List') }}</h2>
                <div class="px-6">
                    <div
                        class="my-5 p-5 bg-white text-black text-base rounded-lg overflow-x-auto overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap relative">
                            <thead class="bg-transparent">
                                <tr class="text-left leading-5">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Price') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Category') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ingredients</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Available') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Stock') }}</th>
                                    {{-- <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Low Stock Threshold</th> --}}
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Is Composable') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($this->products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category?->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($product->ingredients as $ingredient)
                                                {{ $ingredient->name }} ({{ $ingredient->pivot->quantity }})
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($product->is_available)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $product->low_stock_threshold }}
                                            </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->is_composable ? 'Yes' : 'No' }}
                                        </td>
                                        <td class="flex gap-2">
                                            <x-button wire:click="editProduct({{ $product->id }})" type="button"
                                                size="sm" color="primary">
                                                <span class="material-icons">
                                                    edit
                                                </span>
                                            </x-button>
                                            <x-button type="button" color="danger" size="sm"
                                                wire:click="deleteProduct({{ $product->id }})">
                                                <span class="material-icons">
                                                    delete
                                                </span>
                                            </x-button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">No
                                            products
                                            found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- pagination --}}
                        <div class="mt-4">
                            {{ $this->products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
