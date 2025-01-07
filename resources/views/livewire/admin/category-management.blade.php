<div>
    {{-- Analytics Section --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4" x-show="$wire.showAnalytics">
        <x-card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $this->categoryAnalytics['total_categories'] }}</p>
                </div>
                <span class="material-symbols">folder</span>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Active:') }}
                    {{ $this->categoryAnalytics['active_categories'] }}</span>
            </div>
        </x-card>

        <x-card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Product Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $this->categoryAnalytics['product_categories'] }}</p>
                </div>
                <span class="material-symbols">cart</span>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Total Products:') }}
                    {{ $this->categoryAnalytics['total_products'] }}</span>
            </div>
        </x-card>

        <x-card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Ingredient Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $this->categoryAnalytics['ingredient_categories'] }}</p>
                </div>
                <span class="material-symbols">beaker</span>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Total Ingredients:') }}
                    {{ $this->categoryAnalytics['total_ingredients'] }}</span>
            </div>
        </x-card>

        <x-card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Composable Categories') }}</p>
                    <p class="text-2xl font-semibold">{{ $this->categoryAnalytics['composable_categories'] }}</p>
                </div>
                <span class="material-symbols">puzzle</span>
            </div>
        </x-card>
    </div>

    {{-- Filters Section --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-1 flex-wrap items-center gap-4">
            <x-input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search categories...') }}"
                class="max-w-xs" />

            <select wire:model.live="type_filter" class="max-w-xs">
                <option value="">{{ __('All Types') }}</option>
                <option value="product">{{ __('Product Categories') }}</option>
                <option value="ingredient">{{ __('Ingredient Categories') }}</option>
                <option value="composable">{{ __('Composable Categories') }}</option>
            </select>

            <select wire:model.live="parent_filter" class="max-w-xs">
                <option value="">{{ __('Root Categories') }}</option>
                @foreach ($this->parentCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-4">
            <x-button wire:click="$toggle('showAnalytics')" type="button" primary class="gap-2">
                {{ __('Analytics') }}
            </x-button>

            <x-button wire:click="$toggle('showForm')" type="button" primary class="gap-2">
                {{ __('Add Category') }}
            </x-button>
        </div>
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
    {{-- Categories Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <x-table.heading sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">
                        {{ __('Name') }}
                    </x-table.heading>
                    <x-table.heading>{{ __('Type') }}</x-table.heading>
                    <x-table.heading>{{ __('Items') }}</x-table.heading>
                    <x-table.heading>{{ __('Status') }}</x-table.heading>
                    <x-table.heading>{{ __('Actions') }}</x-table.heading>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($this->categories as $category)
                    <tr wire:key="category-{{ $category->id }}">
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-medium">{{ $category->name }}</span>
                            @if ($category->description)
                                <p class="mt-1 text-sm text-gray-500">{{ $category->description }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <x-badge :color="$category->isProductCategory() ? 'success' : 'info'" :title="$category->isProductCategory()
                                ? __('Product Category')
                                : __('Ingredient Category')">
                                {{ $category->isProductCategory() ? __('Product') : __('Ingredient') }}
                            </x-badge>
                            @if ($category->is_composable)
                                <x-badge color="alert" class="ml-2" :title="__('Composable Category')">
                                    {{ __('Composable') }}
                                </x-badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($category->parent)
                                <span class="text-sm">{{ $category->parent->name }}</span>
                            @else
                                <x-badge color="secondary">{{ __('Root') }}</x-badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <x-toggle wire:click="toggleStatus({{ $category->id }})" :value="$category->status" />
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-4">
                                <x-button wire:click="editCategory({{ $category->id }})" primary type="button"
                                    class="gap-2">
                                    <span class="material-symbols">edit</span>
                                </x-button>
                                <x-button wire:click="deleteCategory({{ $category->id }})" negative type="button"
                                    class="gap-2">
                                    <span class="material-symbols">delete</span>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No categories found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Category Form Modal --}}
    <x-modal wire:model="showForm" name="showForm" max-width="2xl">
        <form wire:submit="saveCategory">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input wire:model="name" label="{{ __('Name') }}" required />
                </div>

                <div class="sm:col-span-2">
                    <x-textarea wire:model="description" label="{{ __('Description') }}" />
                </div>

                <div>
                    <select wire:model="type" label="{{ __('Category Type') }}" required>
                        <option value="">{{ __('Select Type') }}</option>
                        <option value="{{ \App\Models\Product::class }}">{{ __('Product Category') }}</option>
                        <option value="{{ \App\Models\Ingredient::class }}">{{ __('Ingredient Category') }}
                        </option>
                    </select>
                </div>

                <div>
                    <select wire:model="parent_id" label="{{ __('Parent Category') }}">
                        <option value="">{{ __('No Parent') }}</option>
                        @foreach ($this->parentCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-4">
                    <x-toggle wire:model="status" label="{{ __('Active') }}" />
                    <x-toggle wire:model="is_composable" label="{{ __('Composable') }}" />
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-4">
                    <x-button warning wire:click="$set('showForm', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" primary>
                        {{ __('Save Category') }}
                    </x-button>
                </div>
            </x-slot>
        </form>
    </x-modal>

    <div class="mt-4">
        {{ $this->categories->links() }}
    </div>
</div>
