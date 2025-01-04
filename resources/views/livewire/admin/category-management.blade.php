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
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold mb-6 text-center">{{ __('Category Management') }}</h2>
            </div>

            <div class="mb-4">
                <x-input type="text" wire:model="search" placeholder="{{ __('Search Categories...') }}" class="w-full" />
            </div>
        </div>

        <!-- Add/Edit Category Form -->
        <form wire:submit.prevent="saveCategory" class="mb-8 bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <input type="text" wire:model="name" placeholder="Category Name"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">
                {{ $categoryId ? __('Update Category') : __('Add Category') }}
            </button>
        </form>

        <!-- Category List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($this->categories as $category)
                <div class="border p-4 rounded-lg shadow-md bg-white">
                    <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
                    <div class="flex space-x-2">
                        <x-button wire:click="editCategory({{ $category->id }})" color="warning">
                            {{ __('Edit') }}
                        </x-button>
                        <x-button wire:click="deleteCategory({{ $category->id }})" color="danger">
                            {{ __('Delete') }}
                        </x-button>
                        <x-button wire:click="toggleStatus({{ $category->id }})"
                            color="{{ $category->status ? 'success' : 'secondary' }}">
                            {{ $category->status ? __('Deactivate') : __('Activate') }}
                        </x-button>
                    </div>
                </div>
            @empty
                <p>{{ __('No categories found') }}</p>
            @endforelse
        </div>
    </div>
</div>