<div>
    <div class="py-12">

        <div class="flex flex-wrap mt-4 px-2">

            <form wire:submit="saveProduct" class="w-1/4">
                <x-accordion :title="$editingProductId ? 'Edit Product' : 'Add New Product'" open="true">
                    <div class="space-y-4 px-4">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-input wire:model="name" id="name" class="block mt-1 w-full" type="text"
                                name="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea wire:model="description" id="description" name="description"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-input wire:model="price" id="price" class="block mt-1 w-full" type="number"
                                name="price" step="0.01" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select wire:model="category_id" id="category_id" class="block mt-1 w-full">
                                <option value="">Select Category</option>
                                <option value="1">Juice</option>
                                <option value="2">Coffee</option>
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="is_available" class="inline-flex items-center">
                                <input wire:model="is_available" id="is_available" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="is_available">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Available') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block mb-2">Stock</label>
                            <x-input type="number" id="stock" wire:model="stock" class="block mt-1 w-full" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Image URL')" />
                            <x-input wire:model="image" id="image" class="block mt-1 w-full" type="url"
                                name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>


                        {{-- <div class="mb-4">
                                <label for="low_stock_threshold" class="block mb-2">Low Stock Threshold</label>
                                <input type="number" id="low_stock_threshold" wire:model="low_stock_threshold"
                                    class="w-full px-3 py-2 border rounded">
                            </div> --}}
                        <div class="mb-4">
                            <label for="is_composable" class="inline-flex items-center">
                                <input type="checkbox" id="is_composable" wire:model="is_composable"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="is_composable">
                                Is Composable
                            </label>
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
