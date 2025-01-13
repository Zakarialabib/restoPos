<div>
    <div class="px-4 mx-auto bg-gray-100 rounded-lg shadow-md">
        <!-- Header Section -->
        <div class="bg-gray-200 rounded-lg shadow-lg px-6 py-2 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                <h2 class="text-3xl font-bold mb-4 md:mb-0 text-white">{{ __('Product Management') }}</h2>
                <div class="flex space-x-4">
                    @if (count($selectedProducts) > 0)
                        <x-button wire:click="$set('showBulkActions', true)" color="secondary" type="button">
                            {{ __('Bulk Actions') }}
                        </x-button>
                    @endif
                    <x-button wire:click="$toggle('showForm')" color="primary" type="button">
                        {{ __('Add Product') }}
                    </x-button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl shadow-inner px-4 py-2">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <x-input wire:model.live="search" type="text" placeholder="{{ __('Search products...') }}" />

                    <select wire:model.live="category_filter" id="category_filter"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}
                                ({{ $category->products_count }})
                            </option>
                        @endforeach
                    </select>

                    <select wire:model.live="status_filter" id="status_filter"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="available">{{ __('Available') }}</option>
                        <option value="unavailable">{{ __('Unavailable') }}</option>
                        <option value="low_stock">{{ __('Low Stock') }}</option>
                        <option value="featured">{{ __('Featured') }}</option>
                    </select>

                </div>
            </div>

            <!-- Analytics Section -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Analytics') }}</h3>

                <!-- Overview Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="border p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">{{ __('Total Products') }}</h4>
                        <p class="text-2xl font-bold">{{ $this->productAnalytics['total_products'] }}</p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">{{ __('Active Products') }}</h4>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $this->productAnalytics['active_products'] }}
                        </p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">{{ __('Low Stock') }}</h4>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $this->productAnalytics['low_stock_count'] }}
                        </p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">{{ __('Featured') }}</h4>
                        <p class="text-2xl font-bold text-blue-600">{{ $this->productAnalytics['featured_count'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Form -->
            @if ($showForm)
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-semibold mb-4">
                        {{ $editingProductId ? __('Edit Product') : __('Add Product') }}
                    </h3>

                    <form wire:submit="saveProduct" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <select id="category_id" wire:model="category_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-label for="cost" :value="__('Cost')" />
                                    <x-input id="cost" wire:model="cost" type="number" step="0.01"
                                        class="w-full" />
                                    @error('cost')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <x-label for="price" :value="__('Price')" />
                                    <x-input id="price" wire:model="price" type="number" step="0.01"
                                        class="w-full" />
                                    @error('price')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
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
                            </div>

                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="status" class="form-checkbox">
                                    <span class="ml-2">{{ __('Active') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_featured" class="form-checkbox">
                                    <span class="ml-2">{{ __('Featured') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_composable" class="form-checkbox">
                                    <span class="ml-2">{{ __('Composable') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-4">
                            <!-- Image Upload -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                <div class="text-center">
                                    @if ($image && !$editingProductId)
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="mx-auto h-48 w-48 object-cover rounded-lg mb-4">
                                    @elseif($editingProductId && $image)
                                        <img src="{{ asset('storage/products/' . $image) }}"
                                            class="mx-auto h-48 w-48 object-cover rounded-lg mb-4">
                                    @else
                                        <span class="material-icons text-gray-400 text-5xl">image</span>
                                        <p class="mt-2 text-sm text-gray-600">{{ __('Upload product image') }}</p>
                                    @endif

                                    <input type="file" wire:model="image" class="hidden" id="image-upload"
                                        accept="image/*">
                                    <label for="image-upload"
                                        class="mt-2 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-200 cursor-pointer">
                                        {{ __('Choose Image') }}
                                    </label>
                                    @error('image')
                                        <span class="block mt-2 text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Size-based Pricing -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-medium text-gray-700">{{ __('Size-based Pricing') }}</h4>
                                    <x-button type="button" wire:click="addSizePrice" color="secondary"
                                        size="sm">
                                        {{ __('Add Size') }}
                                    </x-button>
                                </div>

                                @foreach ($sizePrices as $index => $sizePrice)
                                    <div class="grid grid-cols-2 gap-4 items-center border p-4 rounded-lg">
                                        <div>
                                            <x-label for="sizePrices.{{ $index }}.size" :value="__('Size')" />
                                            <x-input wire:model="sizePrices.{{ $index }}.size" type="text"
                                                class="w-full" />
                                        </div>
                                        <div>
                                            <x-label for="sizePrices.{{ $index }}.unit" :value="__('Unit')" />
                                            <select id="sizePrices.{{ $index }}.unit"
                                                wire:model="sizePrices.{{ $index }}.unit"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="g">{{ __('Grams (g)') }}</option>
                                                <option value="ml">{{ __('Milliliters (ml)') }}</option>
                                                <option value="pcs">{{ __('Pieces') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-label for="sizePrices.{{ $index }}.cost" :value="__('Cost')" />
                                            <x-input wire:model="sizePrices.{{ $index }}.cost" type="number"
                                                step="0.01" class="w-full" />
                                        </div>
                                        <div>
                                            <x-label for="sizePrices.{{ $index }}.price" :value="__('Price')" />
                                            <x-input wire:model="sizePrices.{{ $index }}.price" type="number"
                                                step="0.01" class="w-full" />
                                        </div>
                                        <div class="col-span-2 flex justify-end">
                                            <x-button type="button"
                                                wire:click="removeSizePrice({{ $index }})" color="danger"
                                                size="sm">
                                                {{ __('Remove') }}
                                            </x-button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Ingredients -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-medium text-gray-700">{{ __('Required Ingredients') }}</h4>
                                    <x-button type="button" wire:click="addIngredient" color="secondary"
                                        size="sm">
                                        {{ __('Add Ingredient') }}
                                    </x-button>
                                </div>

                                @foreach ($selectedIngredients as $index => $ingredient)
                                    <div class="grid grid-cols-3 gap-4 items-center border p-4 rounded-lg">
                                        <div>
                                            <x-label for="selectedIngredients.{{ $index }}.id"
                                                :value="__('Ingredient')" />
                                            <select id="selectedIngredients.{{ $index }}.id"
                                                wire:model="selectedIngredients.{{ $index }}.id"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">{{ __('Select Ingredient') }}</option>
                                                @foreach ($this->ingredients as $ing)
                                                    <option value="{{ $ing->id }}">{{ $ing->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <x-label for="selectedIngredients.{{ $index }}.quantity"
                                                :value="__('Quantity')" />
                                            <x-input id="selectedIngredients.{{ $index }}.quantity"
                                                wire:model="selectedIngredients.{{ $index }}.quantity"
                                                type="number" step="0.01" class="w-full" />
                                        </div>
                                        <div>
                                            <x-label for="selectedIngredients.{{ $index }}.unit"
                                                :value="__('Unit')" />
                                            <select id="selectedIngredients.{{ $index }}.unit"
                                                wire:model="selectedIngredients.{{ $index }}.unit"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="g">{{ __('Grams (g)') }}</option>
                                                <option value="ml">{{ __('Milliliters (ml)') }}</option>
                                                <option value="pcs">{{ __('Pieces') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-span-3 flex justify-end">
                                            <x-button type="button"
                                                wire:click="removeIngredient({{ $index }})" color="danger"
                                                size="sm">
                                                {{ __('Remove') }}
                                            </x-button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <div class="flex justify-end space-x-4">
                                <x-button wire:click="$toggle('showForm')" color="secondary" type="button">
                                    {{ __('Cancel') }}
                                </x-button>
                                <x-button type="submit" color="primary">
                                    {{ $editingProductId ? __('Update') : __('Save') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

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

        <!-- Products Grid -->
        <div class="bg-white rounded-lg shadow overflow-hidden" x-data="{
            showAdjustStock: false,
            showPriceHistory: false,
            showPriceHistory: false,
            showStockHistory: false
        }">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @forelse($this->products as $product)
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 relative border-2 border-gray-300">
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

                        <!-- Product Image -->
                        <div class="absolute top-0 right-0 mt-2 mr-2">
                            @if ($product->stock_status === 'out_of_stock')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $product->stock_status_label }}
                                </span>
                            @elseif ($product->stock_status === 'low_stock')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $product->stock_status_label }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $product->stock_status_label }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $product?->category?->name }}</p>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectedProducts"
                                        class="absolute left-0 border-2 border-gray-300" value="{{ $product->id }}"
                                        class="form-checkbox">
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>

                            <!-- Stock Management -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <span class="block text-sm text-gray-600">{{ __('Profit Margin') }}</span>
                                    <span
                                        class="block text-lg font-bold {{ $product->profit_margin >= 20 ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ number_format($product->profit_margin, 1) }}%
                                    </span>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <span class="block text-sm text-gray-600">{{ __('Stock') }}</span>
                                    <span class="block text-lg font-bold"
                                        style="color: {{ $product->stock_status_color }}">
                                        {{ number_format($product->stock_quantity, 2) }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <span class="block text-sm text-gray-600">{{ __('Reorder Point') }}</span>
                                    <span class="block text-lg font-bold text-gray-900">
                                        {{ number_format($product->reorder_point, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Size-based Pricing -->
                            @if ($product->hasSizePrices())
                                <div class="mb-4">
                                    <div class="space-y-2">
                                        @foreach ($product->prices as $price)
                                            @if (isset($price->metadata['size']))
                                                <div
                                                    class="flex justify-between items-center bg-gray-50 rounded-lg p-2">
                                                    <span class="text-sm">
                                                        {{ $price->metadata['size'] }}
                                                        {{ $price->metadata['unit'] }}
                                                    </span>
                                                    <span class="font-medium">
                                                        {{ number_format($price->price, 2) }} DH
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Ingredients List -->
                            @if ($product->is_composable && $product->ingredients->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-600 mb-2">
                                        {{ __('Required Ingredients') }}</h4>
                                    <div class="space-y-2">
                                        @foreach ($product->getRequiredIngredients() as $ingredient)
                                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-2">
                                                <span class="text-sm">{{ $ingredient['name'] }}</span>
                                                <span
                                                    class="font-medium {{ $ingredient['is_available'] ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $ingredient['required_quantity'] }}
                                                    {{ $ingredient['unit'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-2 items-center pt-4 border-t">
                                <x-button wire:click="toggleStatus({{ $product->id }})"
                                    color="{{ $product->status ? 'success' : 'warning' }}" size="sm">
                                    {{ $product->status ? __('Set Inactive') : __('Set Active') }}
                                </x-button>
                                <x-button wire:click="toggleFeatured({{ $product->id }})"
                                    color="{{ $product->is_featured ? 'warning' : 'secondary' }}" size="sm">
                                    {{ $product->is_featured ? __('Set Unfeatured') : __('Set Featured') }}
                                </x-button>
                                <x-button wire:click="editProduct({{ $product->id }})" color="info"
                                    size="sm">
                                    {{ __('Edit') }}
                                </x-button>
                                <x-button wire:click="deleteProduct({{ $product->id }})" color="danger"
                                    size="sm">
                                    {{ __('Delete') }}
                                </x-button>
                            </div>

                            <div class="grid grid-cols-3 gap-2 justify-between items-center pt-4 border-t">
                                <!-- Stock Adjustment Modal -->
                                <x-button @click="showAdjustStock = true" color="secondary" size="sm"
                                    type="button">
                                    {{ __('Adjust Stock') }}
                                </x-button>
                                <!-- Price History Modal -->
                                <x-button @click="showPriceHistory = true" color="secondary" size="sm"
                                    type="button">
                                    {{ __('Price History') }}
                                </x-button>
                                <!-- Stock History Modal -->
                                <x-button @click="showStockHistory = true" color="secondary" size="sm"
                                    type="button">
                                    {{ __('Stock History') }}
                                </x-button>
                            </div>

                            <div x-show="showAdjustStock" x-cloak
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                                    <h3 class="text-lg font-semibold mb-4">{{ __('Adjust Stock') }}</h3>

                                    <div class="space-y-4">
                                        <div>
                                            <x-label for="adjustmentQuantity" :value="__('Quantity')" />
                                            <x-input id="adjustmentQuantity" type="number" step="0.01"
                                                wire:model="adjustmentQuantity" class="w-full" />
                                        </div>

                                        <div>
                                            <x-label for="adjustmentReason" :value="__('Reason')" />
                                            <x-input id="adjustmentReason" type="text"
                                                wire:model="adjustmentReason" class="w-full" />
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <x-button @click="showAdjustStock = false" color="secondary">
                                            {{ __('Cancel') }}
                                        </x-button>
                                        <x-button wire:click="adjustStock({{ $product->id }})" color="primary">
                                            {{ __('Save') }}
                                        </x-button>
                                    </div>
                                </div>
                            </div>

                            <div x-show="showPriceHistory" x-cloak
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
                                    <h3 class="text-lg font-semibold mb-4">{{ __('Price History') }}</h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Date') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Price') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Cost') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Reason') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                {{-- @dump($product->getPriceHistory()) --}}
                                                {{-- @foreach ($product->getPriceHistory() as $history)
                                                        <tr>
                                                            <td class="px-4 py-2">
                                                                {{ $history['effective_date']->format('Y-m-d H:i') }}</td>
                                                            <td class="px-4 py-2">
                                                                {{ number_format($history['price'], 2) }} DH</td>
                                                            <td class="px-4 py-2">
                                                                {{ number_format($history['cost'], 2) }} DH</td>
                                                            <td class="px-4 py-2">{{ $history['reason'] }}</td>
                                                        </tr>
                                                    @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <x-button @click="showPriceHistory = false" color="secondary">
                                            {{ __('Close') }}
                                        </x-button>
                                    </div>
                                </div>
                            </div>

                            <div x-show="showStockHistory" x-cloak
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
                                    <h3 class="text-lg font-semibold mb-4">{{ __('Stock History') }}</h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Date') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Type') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Quantity') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Previous') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('New') }}</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                        {{ __('Reason') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($product->stockLogs()->latest()->get() as $log)
                                                    <tr>
                                                        <td class="px-4 py-2">
                                                            {{ $log->created_at->format('Y-m-d H:i') }}</td>
                                                        <td class="px-4 py-2">
                                                            <span
                                                                class="px-2 py-1 rounded-full text-xs font-medium
                                                                    {{ $log->type === 'increase' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ ucfirst($log->type) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            {{ number_format(abs($log->quantity), 2) }}</td>
                                                        <td class="px-4 py-2">
                                                            {{ number_format($log->previous_quantity, 2) }}</td>
                                                        <td class="px-4 py-2">
                                                            {{ number_format($log->new_quantity, 2) }}</td>
                                                        <td class="px-4 py-2">{{ $log->reason }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <x-button @click="showStockHistory = false" color="secondary">
                                            {{ __('Close') }}
                                        </x-button>
                                    </div>
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

            <div class="p-4 border-t">
                {{ $this->products->links() }}
            </div>
        </div>

        <!-- Bulk Actions Modal -->
        @if ($showBulkActions)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Bulk Actions') }}</h3>

                    <div class="space-y-4">
                        <div>
                            <x-label for="category_id" :value="__('Update Category')" />
                            <select id="category_id" wire:model="category_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="adjustmentQuantity" :value="__('Adjust Stock')" />
                            <div class="flex space-x-2">
                                <x-input wire:model="adjustmentQuantity" type="number" step="0.01"
                                    class="w-full" placeholder="{{ __('Quantity') }}" />
                                <x-input wire:model="adjustmentReason" type="text" class="w-full"
                                    placeholder="{{ __('Reason') }}" />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <x-button wire:click="$toggle('showBulkActions')" color="secondary">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button wire:click="bulkUpdateCategory" color="primary">
                                {{ __('Update Category') }}
                            </x-button>
                            <x-button wire:click="bulkAdjustStock" color="success">
                                {{ __('Adjust Stock') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
