<div>
    <div class="w-full">
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

        <!-- Header with Analytics -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Product Management') }}</h2>
                <p class="text-sm text-gray-600">{{ __('Manage your product catalog') }}</p>
            </div>
            <div class="flex gap-4">
                <x-input type="text" wire:model.live="search" class="w-full rounded border-gray-300"
                    placeholder="{{ __('Search products...') }}" />
                <select wire:model.live="category_id" class="rounded border-gray-300">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4">
                <x-button wire:click="addProduct" color="primary">
                    <span class="material-icons">add</span>
                    {{ __('Add Product') }}
                </x-button>
                <x-button wire:click="$toggle('showAnalytics')" color="success">
                    <span class="material-icons">{{ $showAnalytics ? 'visibility_off' : 'visibility' }}</span>
                    {{ $showAnalytics ? __('Hide Analytics') : __('Show Analytics') }}
                </x-button>
                <!-- Added Export Button -->
                <x-button wire:click="exportProducts" color="secondary">
                    <span class="material-icons">download</span>
                    {{ __('Export Products') }}
                </x-button>
            </div>
        </div>

        @if ($showAnalytics)
            <div class="grid grid-cols-4 gap-4 mb-6">
                <x-stat-card title="{{ __('Total Products') }}" :value="$this->productAnalytics['total_products']" icon="box" />
                {{-- <x-stat-card title="{{ __('Total Value') }}" :value="number_format($this->productAnalytics['total_value'], 2) . ' DH'" icon="currency-dollar" /> --}}
                <x-stat-card title="{{ __('Active Categories') }}" :value="$this->productAnalytics['active_categories']" icon="folder" />
                <!-- Added Low Stock Alert Card -->
                <x-stat-card title="{{ __('Low Stock Items') }}" :value="$this->productAnalytics['low_stock_count']" icon="warning" color="red" />
            </div>
        @endif

        <!-- Product Form -->
        @if ($showForm)
            <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
                <h3 class="text-xl font-semibold text-indigo-700 mb-6">
                    {{ $editingProductId ? __('Edit Product') : __('Create New Product') }}
                </h3>

                <form wire:submit="saveProduct">
                    <div class="grid grid-cols-3 gap-8">
                        <!-- Basic Info Section -->
                        <div class="col-span-1 bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-indigo-800 mb-4">{{ __('Basic Information') }}</h4>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="name" :value="__('Product Name')" />
                                    <x-input type="text" id="name" wire:model="name" class="w-full mt-1" />
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" />
                                    <select id="category_id" wire:model="category_id"
                                        class="w-full mt-1 rounded-md border-gray-300">
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
                                    <x-input-label for="prices" :value="__('Prices (DH)')" />
                                    <div class="space-y-4">
                                        @forelse ($this->prices as $index => $price)
                                            <div class="border p-4 rounded-lg flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <x-input type="number" id="price_{{ $index }}"
                                                        wire:model="prices.{{ $index }}.price"
                                                        class="w-full mt-1" step="0.01" />
                                                </div>
                                                <div class="flex-shrink-0">
                                                    @if ($price['size'])
                                                        <span
                                                            class="text-lg font-semibold mr-2">{{ $price['size'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="border p-4 rounded-lg flex items-center justify-between mb-2">
                                                <span class="text-sm text-gray-500">No prices found</span>
                                            </div>
                                        @endforelse
                                    </div>
                                    @error('prices.*.price')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-4 mt-4">
                                <div class="border-2 border-dashed border-emerald-200 rounded-lg p-4">
                                    <div class="text-center">
                                        @if ($image && !$editingProductId)
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="mx-auto h-48 w-48 object-cover rounded-lg mb-4">
                                        @elseif ($editingProductId && $image)
                                            <img src="{{ asset('products/' . $image) }}"
                                                class="mx-auto h-48 w-48 object-cover rounded-lg mb-4">
                                        @else
                                            <span class="material-icons text-emerald-400 text-5xl">image</span>
                                            <p class="mt-2 text-sm text-emerald-600">{{ __('Upload product image') }}
                                            </p>
                                        @endif

                                        <input type="file" wire:model="image" class="hidden" id="image-upload"
                                            accept="image/*">
                                        <label for="image-upload"
                                            class="mt-2 inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-emerald-200 cursor-pointer">
                                            {{ __('Choose Image') }}
                                        </label>
                                        @error('image')
                                            <span class="block mt-2 text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-span-1 bg-blue-50 p-6 rounded-lg">

                            <h4 class="font-semibold text-emerald-800 mb-4">{{ __('Details & Media') }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <x-textarea id="description" wire:model="description" class="w-full mt-1"
                                        rows="4" />
                                    @error('description')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-4 mt-4">
                                <div class="flex items-center justify-between p-4 bg-white rounded-lg">
                                    <x-checkbox wire:model="status" color="blue" :label="__('Available for Sale')" />
                                    <p class="text-sm text-gray-500">
                                        {{ __('Make this product available in store') }}</p>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-white rounded-lg"> 
                                    <x-checkbox color="blue" wire:model="is_featured" color="blue"
                                        :label="__('Featured Product')" />
                                    <p class="text-sm text-gray-500">{{ __('Show in featured section') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Settings & Recipe Section -->
                        <div class="col-span-1 bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-amber-800 mb-4">{{ __('Recipe Management') }}</h4>

                            <!-- Recipe Section -->
                            @if ($editingProductId && $editingProductId->recipe)
                                <div class="mt-4">
                                    <h4 class="font-semibold">{{ __('Current Recipe Instructions') }}</h4>
                                    <ul class="list-disc pl-5">
                                        @foreach ($editingProductId->recipe->instructions as $instruction)
                                            <li>{{ $instruction }}</li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-4 w-full flex justify-end">
                                        <x-button wire:click="toggleRecipeForm({{ $editingProductId }})"
                                            color="secondary">
                                            {{ __('Edit Recipe') }}
                                        </x-button>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4">
                                    <x-button wire:click="toggleRecipeForm()" color="secondary">
                                        {{ __('Add Recipe') }}
                                    </x-button>
                                </div>
                            @endif

                            @if ($showRecipeForm)
                                <div class="border p-4 rounded-lg mt-4">
                                    <h4 class="font-medium">{{ __('Add or Edit Recipe Instructions') }}</h4>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach ($recipeInstructions as $index => $instruction)
                                            <div class="flex flex-col items-center gap-2">
                                                <x-input type="text"
                                                    wire:model="recipeInstructions.{{ $index }}"
                                                    class="w-full rounded border-gray-300"
                                                    placeholder="{{ __('Instruction') }}" />
                                                <div class="flex gap-2">
                                                    <x-button type="button"
                                                        wire:click="removeInstruction({{ $index }})"
                                                        color="danger">
                                                        <span class="material-icons text-red-500">delete</span>
                                                    </x-button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <x-button type="button" wire:click="addRecipeInstruction" color="secondary"
                                        class="mt-2">
                                        {{ __('Add Instruction') }}
                                    </x-button>
                                </div>
                            @endif

                            <!-- Form Actions -->
                            <div class="mt-8 flex justify-end gap-4">
                                <x-button type="button" color="secondary" wire:click="$set('showForm', false)">
                                    {{ __('Cancel') }}
                                </x-button>
                                <x-button type="submit" color="primary">
                                    <span
                                        class="material-icons mr-2">{{ $editingProductId ? 'update' : 'save' }}</span>
                                    {{ $editingProductId ? __('Update Product') : __('Create Product') }}
                                </x-button>
                            </div>
                        </div>

                        <!-- Stock Management -->
                        <div class="col-span-1 bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-4">{{ __('Stock Management') }}</h4>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="stock" :value="__('Current Stock')" />
                                    <x-input type="number" wire:model="stock" id="stock" class="w-full"
                                        step="1" min="0" />
                                    @error('stock')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="reorder_point" :value="__('Reorder Point')" />
                                    <x-input type="number" wire:model="reorder_point" id="reorder_point"
                                        class="w-full" step="1" min="0" />
                                    @error('reorder_point')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="cost" :value="__('Cost')" />
                                    <x-input type="number" wire:model="cost" id="cost" class="w-full"
                                        step="0.01" min="0" />
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ __('Calculated from ingredients: ') }}
                                        {{ $editingProductId ? number_format($this->calculateProductCost($editingProductId), 2) : '0.00' }}
                                        DH
                                    </p>
                                </div>
                            </div>

                            <!-- Ingredient Requirements -->
                            <div class="mt-6">
                                <h5 class="font-medium mb-2">{{ __('Required Ingredients') }}</h5>
                                @if ($selectedIngredients)
                                    @foreach ($selectedIngredients as $index => $ingredient)
                                        <div class="flex items-center gap-2 mb-2">
                                            <select wire:model="selectedIngredients.{{ $index }}.id"
                                                class="w-2/3 rounded border-gray-300">
                                                <option value="">{{ __('Select Ingredient') }}</option>
                                                @foreach ($this->ingredients as $ing)
                                                    <option value="{{ $ing->id }}">{{ $ing->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input type="number"
                                                wire:model="selectedIngredients.{{ $index }}.quantity"
                                                class="w-1/3" step="0.01" min="0"
                                                placeholder="{{ __('Qty') }}" />
                                        </div>
                                    @endforeach
                                @endif

                                <x-button type="button" wire:click="addIngredientField" color="secondary">
                                    {{ __('Add Ingredient') }}
                                </x-button>
                            </div>
                        </div>

                        <!-- Pricing Management -->
                        <div class="col-span-1 bg-blue-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-4">{{ __('Pricing Management') }}</h4>

                            <!-- Size-based Pricing -->
                            <div class="space-y-4 mb-6">
                                <h5 class="font-medium text-gray-700">{{ __('Size-based Pricing') }}</h5>
                                @foreach ($sizePrices as $index => $sizePrice)
                                    <div class="grid grid-cols-2 gap-4 items-center border p-4 rounded-lg">
                                        <div class="flex-1">
                                            <x-input-label :value="__('Size')" />
                                            <x-input type="text" wire:model="sizePrices.{{ $index }}.size"
                                                class="w-full" />
                                        </div>
                                        <div class="flex-1">
                                            <x-input-label :value="__('Unit')" />
                                            <select wire:model="sizePrices.{{ $index }}.unit"
                                                class="w-full rounded-md border-gray-300">
                                                <option value="g">{{ __('Grams (g)') }}</option>
                                                <option value="ml">{{ __('Milliliters (ml)') }}</option>
                                                <option value="pcs">{{ __('Pieces') }}</option>
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <x-input-label :value="__('Cost')" />
                                            <x-input type="number" wire:model="sizePrices.{{ $index }}.cost"
                                                class="w-full" step="0.01" />
                                        </div>
                                        <div class="flex-1">
                                            <x-input-label :value="__('Price')" />
                                            <x-input type="number" wire:model="sizePrices.{{ $index }}.price"
                                                class="w-full" step="0.01" />
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" wire:click="removeSizePrice({{ $index }})"
                                                class="p-2 text-red-500 hover:text-red-700">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Price History -->
                            <div class="mt-6">
                                <h5 class="font-medium text-gray-700 mb-4">{{ __('Price History') }}</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2">{{ __('Size') }}</th>
                                                <th class="px-4 py-2">{{ __('Unit') }}</th>
                                                <th class="px-4 py-2">{{ __('Cost') }}</th>
                                                <th class="px-4 py-2">{{ __('Price') }}</th>
                                                <th class="px-4 py-2">{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($this->priceHistory as $price)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $price->metadata['size'] ?? '-' }}</td>
                                                    <td class="px-4 py-2">{{ $price->metadata['unit'] ?? '-' }}</td>
                                                    <td class="px-4 py-2">{{ number_format($price->cost, 2) }}</td>
                                                    <td class="px-4 py-2">{{ number_format($price->price, 2) }}</td>
                                                    <td class="px-4 py-2">{{ $price->date }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <x-input type="text" wire:model.live="search" placeholder="{{ __('Search products...') }}"
                    class="w-full" />
            </div>
            <select wire:model.live="category_filter" class="rounded-md border-gray-300">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="status_filter" class="rounded-md border-gray-300">
                <option value="">{{ __('All Status') }}</option>
                <option value="available">{{ __('Available') }}</option>
                <option value="unavailable">{{ __('Unavailable') }}</option>
                <option value="low_stock">{{ __('Low Stock') }}</option>
            </select>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->products as $product)
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 relative">
                    <!-- Featured Badge -->
                    @if ($product->is_featured)
                        <div class="absolute top-0 right-0 mt-4 mr-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ __('Featured') }}
                            </span>
                        </div>
                    @endif
                    <div class="absolute top-0 right-4 mt-4">
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status ? __('Available') : __('Out of Stock') }}
                        </span>
                    </div>
                    <div
                        class="text-center p-6 shadow-lg border-2 border-retro-orange rounded-lg overflow-hidden bg-white hover:bg-orange-50">
                        <!-- Product Info -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>

                        <!-- Prices Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            @foreach ($product->prices as $price)
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <span class="block text-sm text-gray-600">{{ $price->size }}
                                        {{ $price->unit }}</span>
                                    <span class="block text-lg font-bold text-indigo-600">{{ $price->price }}
                                        DH</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Recipe Preview -->
                        @if ($product->recipe)
                            <div class="mb-4">
                                <div class="flex items-center text-gray-700 mb-2">
                                    <span class="material-icons mr-2">restaurant_menu</span>
                                    <span class="font-medium">{{ __('Recipe Available') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex space-x-2">
                                <button wire:click="toggleAvailability({{ $product->id }})"
                                    class="p-2 rounded-full hover:bg-gray-100">
                                    <span
                                        class="material-icons {{ $product->status ? 'text-green-600' : 'text-red-600' }}">
                                        power_settings_new
                                    </span>
                                </button>
                                <button wire:click="toggleFeatured({{ $product->id }})"
                                    class="p-2 rounded-full hover:bg-gray-100 transparent">
                                    <span
                                        class="material-icons {{ $product->is_featured ? 'text-yellow-500' : 'text-gray-400' }}">
                                        star
                                    </span>
                                </button>
                            </div>
                            <div class="flex space-x-2">
                                <x-button type="button" wire:click="editProduct({{ $product->id }})"
                                    color="info" class="rounded-full">
                                    <span class="material-icons">edit</span>
                                </x-button>

                                <x-button type="button" wire:click="deleteProduct({{ $product->id }})"
                                    color="danger" class="rounded-full">
                                    <span class="material-icons">delete</span>
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    {{ __('No products found.') }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $this->products->links() }}
        </div>
    </div>
</div>
