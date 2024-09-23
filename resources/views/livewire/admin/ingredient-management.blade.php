<div>
    <h2 class="text-2xl font-bold mb-4">Ingredient Management</h2>

    <!-- Add Product Form -->
    <form wire:submit="addProduct" class="mb-8">
        <input type="text" wire:model="newProduct.name" placeholder="Product Name" class="mb-2">
        <input type="number" wire:model="newProduct.price" placeholder="Price" class="mb-2">
        <select wire:model="newProduct.category_id" class="mb-2">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select multiple wire:model="newProduct.ingredients" class="mb-2">
            @foreach ($ingredients as $ingredient)
                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
    </form>

    <!-- Product List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <div class="border p-4 rounded">
                <h3 class="text-xl font-bold">{{ $product->name }}</h3>
                <p>Price: ${{ $product->price }}</p>
                <p>Category: {{ $product->category->name }}</p>
                {{-- <p>Ingredients: --}}
                {{-- {{ $product->ingredients }} --}}
                {{-- </p> --}}
                <button wire:click="editProduct({{ $product->id }})"
                    class="bg-yellow-500 text-white px-2 py-1 rounded mt-2">Edit</button>
            </div>)
        @endforeach
    </div>

    <!-- Edit Product Modal -->
    @if ($editingProduct)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-bold mb-4">Edit Product</h3>
                <input type="text" wire:model="editingProduct.name" class="mb-2 w-full">
                <input type="number" wire:model="editingProduct.price" class="mb-2 w-full">
                <input type="number" wire:model="editingProduct.volume" class="mb-2 w-full"> <!-- Add volume input -->
                <textarea wire:model="editingProduct.instructions" class="mb-2 w-full"></textarea> <!-- Add instructions input -->
                <select wire:model="editingProduct.category_id" class="mb-2 w-full">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select multiple wire:model="editingProduct.ingredients" class="mb-2 w-full">
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end">
                    <button wire:click="updateProduct"
                        class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Save</button>
                    <button wire:click="$set('editingProduct', null)"
                        class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
