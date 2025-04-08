<div>
    <x-theme.breadcrumb :title="__('Category Management')" :parent="route('admin.categories')" :parentName="__('Category Management')">
            <div class="flex space-x-4">
            <x-button wire:click="$wire.showAnalytics = true" color="secondary" type="button">
                {{ $showAnalytics ? __('Hide Analytics') : __('Show Analytics') }}
            </x-button>
            <x-button wire:click="$wire.showForm = true" color="primary" type="button">
                {{ __('Add Category') }}
            </x-button>
        </div>
    </x-theme.breadcrumb>
    
    {{-- Search and Filters --}}
    <div class="flex flex-wrap items-center gap-x-4">
        <div class="flex-1 min-w-[200px]">
            <x-input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search categories...') }}"
                icon="search" class="w-full border border-gray-300 rounded-md" />
        </div>

        <div class="flex gap-x-4 flex-wrap">
            <x-select wire:model.live="type_filter" :options="$this->categoryTypes" placeholder="{{ __('All Types') }}"
                class="w-full border border-gray-300 rounded-md" />

            <x-select wire:model.live="parent_filter" :options="$this->parentCategories->pluck('name', 'id')" placeholder="{{ __('Root Categories') }}"
                class="w-full border border-gray-300 rounded-md" />
        </div>
    </div>

    {{-- Analytics Cards --}}
    <div class="grid grid-cols-1 gap-x-6 sm:grid-cols-2 lg:grid-cols-4" x-show="$wire.showAnalytics" x-transition>
        {{-- Total Categories Card --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-purple-900">{{ __('Categories') }}</h3>
                <p class="mt-2 text-3xl font-bold text-purple-700">{{ $this->analytics['total'] }}</p>
                <p class="mt-1 text-sm text-purple-600">
                    {{ $this->analytics['active'] }} {{ __('active') }}
                </p>
            </div>
            <div class="rounded-full bg-purple-200 p-3">
                <span class="material-symbols text-purple-700">category</span>
            </div>
        </div>

        {{-- Type-specific Cards --}}
        @foreach ($this->analytics['types'] as $type => $stats)
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-{{ $stats['color']['text'] }}">
                        {{ $stats['label'] }}
                    </h3>
                    <p class="mt-2 text-3xl font-bold text-{{ $stats['color']['text'] }}">
                        {{ $stats['total'] }}
                    </p>
                    <p class="mt-1 text-sm text-{{ $stats['color']['text'] }}">
                        {{ $stats['active'] }} {{ __('active') }}
                    </p>
                </div>
                <div class="rounded-full bg-{{ $stats['color']['bg'] }} p-3">
                    <span class="material-symbols text-{{ $stats['color']['text'] }}">
                        {{-- {{ $stats['icon'] }} --}}
                    </span>
                </div>
            </div>
        @endforeach
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

    {{-- Category Form --}}
    <div x-show="$wire.showForm" class="mt-4" x-cloak x-transition>
        <form wire:submit="saveCategory" class="bg-white p-6 rounded-lg shadow-md">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input wire:model="name" label="{{ __('Name') }}" required
                        class="w-full border border-gray-300 rounded-md" />
                </div>

                <div class="sm:col-span-2">
                    <x-textarea wire:model="description" label="{{ __('Description') }}"
                        class="w-full border border-gray-300 rounded-md" />
                </div>

                <div>
                    <select wire:model="type" label="{{ __('Category Type') }}" required
                        class="w-full border border-gray-300 rounded-md">
                        <option value="">{{ __('Select Type') }}</option>

                        @foreach ($this->categoryTypes as $key => $type)
                            <option value="{{ $key }}" {{ $key == $type ? 'selected' : '' }}>
                                {{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select wire:model="parent_id" label="{{ __('Parent Category') }}"
                        class="w-full border border-gray-300 rounded-md">
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
                    <x-button warning wire:click="$set('showForm', false)" class="bg-yellow-500 hover:bg-yellow-600">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" primary class="bg-green-500 hover:bg-green-600">
                        {{ __('Save Category') }}
                    </x-button>
                </div>
            </x-slot>
        </form>
    </div>


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
                            {{ $category->type ? $category->type->label() : __('Undefined Type') }}
                            @if ($category->is_composable)
                                <x-badge color="alert" class="ml-2" :title="__('Composable Category')">
                                    {{ __('Composable') }}
                                </x-badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($category->parent)
                                <span class="text-sm">Child of {{ $category->parent->name }}</span>
                            @else
                                <x-badge color="secondary">{{ __('Root') }}</x-badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <x-toggle wire:click="toggleStatus({{ $category->id }})" :value="$category->status" />
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-4">
                                <x-button wire:click="editCategory('{{ $category->id }}')" primary type="button"
                                    class="gap-2 bg-blue-500 hover:bg-blue-600">
                                    <span class="material-symbols">edit</span>
                                </x-button>
                                <x-button wire:click="deleteCategory('{{ $category->id }}')" negative type="button"
                                    class="gap-2 bg-red-500 hover:bg-red-600">
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

    <div class="mt-4">
        {{ $this->categories->links() }}
    </div>
</div>
