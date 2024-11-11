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
            </div>
        </div>

        @if ($showAnalytics)
            <div class="grid grid-cols-4 gap-4 mb-6">
                <x-stat-card title="{{ __('Total Products') }}" :value="$this->productAnalytics['total_products']" icon="box" />
                <x-stat-card title="{{ __('Total Value') }}" :value="number_format($this->productAnalytics['total_value'], 2) . ' DH'" icon="currency-dollar" />
                <x-stat-card title="{{ __('Active Categories') }}" :value="$this->productAnalytics['active_categories']" icon="folder" />
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
                        <div class="col-span-1 bg-indigo-50 p-6 rounded-lg">
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
                                    <x-input-label for="price" :value="__('Price (DH)')" />
                                    <x-input type="number" id="price" wire:model="price" class="w-full mt-1"
                                        step="0.01" />
                                    @error('price')
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
                                            <img src="{{ Storage::url($image) }}"
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
                        <div class="col-span-1 bg-emerald-50 p-6 rounded-lg">
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
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-white rounded-lg">
                                    <div>
                                        <h4 class="font-medium">{{ __('Available for Sale') }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ __('Make this product available in store') }}</p>
                                    </div>
                                    <x-checkbox wire:model="is_available" />
                                </div>

                                <div class="flex items-center justify-between p-4 bg-white rounded-lg">
                                    <div>
                                        <h4 class="font-medium">{{ __('Featured Product') }}</h4>
                                        <p class="text-sm text-gray-500">{{ __('Show in featured section') }}</p>
                                    </div>
                                    <x-checkbox wire:model="is_featured" />
                                </div>
                            </div>
                        </div>

                        <!-- Settings & Recipe Section -->
                        <div class="col-span-1 bg-amber-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-amber-800 mb-4">{{ __('Recipe') }}</h4>

                            <!-- Recipe Section -->
                            @if ($editingProductId && \App\Models\Product::find($editingProductId)->recipe)
                                <div class="mt-4">
                                    <h4 class="font-semibold">{{ __('Recipe Instructions') }}</h4>
                                    <ul class="list-disc pl-5">
                                        @foreach (\App\Models\Product::find($editingProductId)->recipe->instructions as $instruction)
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
                                    <x-button wire:click="toggleRecipeForm({{ $editingProductId }})"
                                        color="secondary">
                                        {{ __('Add Recipe') }}
                                    </x-button>
                                </div>
                            @endif

                            @if ($showRecipeForm)
                                <div class="border p-4 rounded-lg mt-4">
                                    <h4 class="font-medium">{{ __('Recipe Instructions') }}</h4>
                                    <div class="space-y-2">
                                        @foreach ($recipeInstructions as $index => $instruction)
                                            <div class="flex gap-2">
                                                <input type="text"
                                                    wire:model="recipeInstructions.{{ $index }}"
                                                    class="w-full rounded border-gray-300">
                                                <x-button type="button"
                                                    wire:click="removeInstruction({{ $index }})"
                                                    color="danger">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </x-button>
                                            </div>
                                        @endforeach
                                    </div>
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
                                        {{ $editingProductId ? number_format($this->calculateProductCost(\App\Models\Product::find($editingProductId)), 2) : '0.00' }}
                                        DH
                                    </p>
                                </div>
                            </div>

                            <!-- Ingredient Requirements -->
                            <div class="mt-6">
                                <h5 class="font-medium mb-2">{{ __('Required Ingredients') }}</h5>
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
                                <x-button type="button" wire:click="addIngredientField" color="secondary">
                                    {{ __('Add Ingredient') }}
                                </x-button>
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
                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-lg font-bold text-indigo-600">{{ $product->price }} DH</span>
                            </div>
                        </div>

                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($product->description, 100) }}</p>

                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex gap-2">
                                @if ($product->is_featured)
                                    <x-badge color="alert">{{ __('Featured') }}</x-badge>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <x-button type="button" wire:click="editProduct({{ $product->id }})"
                                    color="info">
                                    <span class="material-icons">edit</span>
                                </x-button>
                                <x-button type="button" wire:click="deleteProduct({{ $product->id }})"
                                    color="danger">
                                    <span class="material-icons">delete</span>
                                </x-button>
                            </div>
                        </div>

                        <!-- Recipe Display -->
                        @if ($product->recipe)
                            <div class="mt-4">
                                <h4 class="font-semibold">{{ __('Recipe Instructions') }}</h4>
                                <ul class="list-disc pl-5">
                                    @foreach ($product->recipe->instructions as $instruction)
                                        <li>{{ $instruction }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
