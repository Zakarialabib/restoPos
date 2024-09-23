<div>
    <h2 class="text-2xl font-bold mb-4">Product Management</h2>

    <!-- Product Creation/Edit Form -->
    <form wire:submit="saveProduct" class="space-y-4">
        <div>
            <input type="text" wire:model="name" placeholder="Product Name" class="w-full p-2 border rounded">
            @error('name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <select wire:model="volume" class="w-full p-2 border rounded">
                <option value="300">300ml</option>
                <option value="500">500ml</option>
            </select>
            @error('volume')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <textarea wire:model="instructions" placeholder="Instructions" class="w-full p-2 border rounded"></textarea>
            @error('instructions')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Ingredients Section -->
        <div>
            <h4 class="font-semibold mb-2">Select Ingredients:</h4>
            <div class="space-y-2">
                @foreach ($this->availableIngredients as $ingredient)
                    <div class="flex items-center space-x-2">
                        <button type="button" wire:click="addIngredient({{ $ingredient->id }})"
                            class="px-2 py-1 bg-blue-500 text-white rounded">Add</button>
                        <span>{{ $ingredient->name }}</span>
                    </div>
                @endforeach
            </div>
            @if ($this->selectedIngredients)
                <div class="mt-4 space-y-2">
                    @foreach ($this->selectedIngredients as $ingredientId => $data)
                        <div class="flex items-center space-x-2">
                            <input type="number" wire:model="selectedIngredients.{{ $ingredientId }}.quantity"
                                placeholder="Quantity" class="w-20 p-1 border rounded">
                            <span>{{ $this->ingredients->find($ingredientId)->name }}</span>
                            <button type="button" wire:click="removeIngredient({{ $ingredientId }})"
                                class="px-2 py-1 bg-red-500 text-white rounded">Remove</button>
                        </div>
                    @endforeach
                </div>
                @error('selectedIngredients')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            @endif
        </div>

        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">
            {{ $editingProductId ? 'Update Product' : 'Create Product' }}
        </button>
    </form>

    <!-- Product List -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-2">Existing Products</h3>
        <div class="space-y-4">
            {{-- @foreach ($this->recipes as $recipe)
                <div class="p-4 bg-gray-100 rounded">
                    <h4 class="text-lg font-semibold">{{ $recipe->name }} ({{ $recipe->volume }}ml)</h4>
                    <p>{{ $recipe->description }}</p>
                    <h5 class="font-semibold mt-2">Ingredients:</h5>
                    <ul class="list-disc list-inside">
                        @foreach ($recipe->ingredients as $ingredient)
                            <li>{{ $ingredient->name }} ({{ $ingredient->pivot->quantity }}g)</li>
                        @endforeach
                    </ul>
                    <div class="mt-2">
                        <button wire:click="editProduct({{ $recipe->id }})"
                            class="px-2 py-1 bg-blue-500 text-white rounded">Edit</button>
                        <button wire:click="deleteProduct({{ $recipe->id }})"
                            class="px-2 py-1 bg-red-500 text-white rounded">Delete</button>
                    </div>
                </div>
            @endforeach --}}
        </div>
    </div>
</div>
