<div>
    <div class="w-full">
        <h2 class="text-3xl font-bold mb-6 text-center">Category Management</h2>

        <!-- Add/Edit Category Form -->
        <form wire:submit="saveCategory" class="mb-8 bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <input type="text" wire:model="name" placeholder="Category Name" class="w-full p-2 border rounded">
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                {{ $categoryId ? 'Update Category' : 'Add Category' }}
            </button>
        </form>

        <!-- Category List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
                <div class="border p-4 rounded-lg shadow-md bg-white">
                    <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
                    <div class="flex justify-between">
                        <button wire:click="editCategory({{ $category->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button wire:click="deleteCategory({{ $category->id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
