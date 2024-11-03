<div>
    <div class="w-full">
        <!-- Header with Analytics -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Product Management') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('Manage your product catalog') }}</p>
                </div>
                <div class="flex gap-4">
                    <button wire:click="$toggle('showForm')"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        {{ $showForm ? __('Hide Form') : __('Add Product') }}
                    </button>
                    <button wire:click="$toggle('showAnalytics')"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        {{ $showAnalytics ? __('Hide Analytics') : __('Show Analytics') }}
                    </button>
                </div>
            </div>

            @if ($showAnalytics)
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <x-stat-card title="{{ __('Total Products') }}" :value="$this->totalProducts" icon="box" />
                    {{-- <x-stat-card title="{{ __('Low Stock') }}" :value="$this->lowStockCount" icon="alert-triangle" color="red" /> --}}
                    <x-stat-card title="{{ __('Total Value') }}" :value="number_format($this->inventoryValue, 2) . ' DH'" icon="currency-dollar" />
                    <x-stat-card title="{{ __('Active Categories') }}" :value="$this->activeCategories" icon="folder" />
                </div>
            @endif
        </div>

        <!-- Product Form -->
        @if ($showForm)
            <form wire:submit="saveProduct" class="mb-8 bg-gray-50 p-6 rounded-lg">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">{{ __('Basic Information') }}</h3>

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-input type="text" id="name" name="name" wire:model="name" class="w-full" />
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" wire:model="category_id"
                                class="w-full rounded-md border-gray-300">
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
                            <x-input-label for="description" :value="__('Description')" />
                            <x-textarea id="description" name="description" wire:model="description" class="w-full"
                                rows="3" />
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">{{ __('Pricing & Stock') }}</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-input type="number" id="price" name="price" wire:model="price" class="w-full"
                                    step="0.01" />
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="space-y-2 pt-4">
                            <div class="flex items-center">
                                <x-checkbox wire:model="is_available" />
                                <span class="ml-2">{{ __('Available for Sale') }}</span>
                            </div>
                            <div class="flex items-center">
                                <x-checkbox wire:model="is_featured" />
                                <span class="ml-2">{{ __('Featured Product') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mt-6">
                    <x-input-label for="image" :value="__('Product Image')" />
                    <div class="mt-2 flex items-center">
                        <input type="file" wire:model="image" accept="image/*" class="hidden" id="image-upload">
                        <label for="image-upload"
                            class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-md">
                            {{ __('Choose Image') }}
                        </label>
                        @if ($image)
                            <div class="ml-4">
                                <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded">
                            </div>
                        @endif
                    </div>
                    @error('image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-4">
                    <x-button type="button" color="secondary" wire:click="$set('showForm', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" color="success">
                        {{ $editingProductId ? __('Update Product') : __('Create Product') }}
                    </x-button>
                </div>
            </form>
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
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-lg font-bold">{{ $product->price }}</span>
                                {{-- <x-badge :color="$product->stock_status_color">
                                    {{ $product->stock_status }}
                                </x-badge> --}}
                            </div>
                        </div>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ Str::limit($product->description, 100) }}
                        </p>

                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex gap-2">
                                @if ($product->is_featured)
                                    <x-badge color="alert">{{ __('Featured') }}</x-badge>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <x-button type="button" wire:click="editProduct({{ $product->id }})"
                                    color="info">
                                    <span class="material-icons text-blue-500">edit</span>
                                </x-button>
                                <x-button type="button" wire:click="deleteProduct({{ $product->id }})"
                                    color="danger">
                                    <span class="material-icons text-red-500">delete</span>
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
