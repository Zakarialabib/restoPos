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
        <!-- Enhanced Header with Filters -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Recipe Management') }}</h2>
                <p class="text-sm text-gray-600">{{ __('Create and manage product recipes') }}</p>
            </div>
            <div class="flex gap-4">
                <x-input type="text" wire:model.live.debounce.300ms="searchRecipe"
                    placeholder="{{ __('Search recipes...') }}" class="w-64" />
                <x-input type="text" wire:model.live.debounce.300ms="searchIngredient"
                    placeholder="{{ __('Search ingredients...') }}" class="w-64" />
                <select wire:model.live="selectedType" class="rounded-md border-gray-300">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach ($this->recipeTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <x-button type="button" wire:click="createRecipe" color="primary">
                <span class="material-icons mr-2">add</span>
                {{ __('Create New Recipe') }}
            </x-button>
        </div>

        <!-- Enhanced Recipe Form -->
        @if ($showForm)
            <form wire:submit="saveRecipe" class="bg-white rounded-lg shadow-lg mb-6 p-6">
                <h3 class="text-xl font-semibold text-indigo-700 mb-6">
                    {{ $editingRecipeId ? __('Edit Recipe') : __('Create New Recipe') }}
                </h3>
                <!-- Basic Info -->
                <div class="grid grid-cols-3 gap-8">
                    <div class="col-span-1 bg-indigo-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-indigo-800 mb-4">{{ __('Basic Information') }}</h4>
                        <div class="space-y-4">
                            <div class="border-2 border-dashed border-emerald-200 rounded-lg p-4">

                                <div>
                                    <x-input-label for="name" :value="__('Recipe Name')" />
                                    <x-input type="text" wire:model="name" class="w-full" />
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <x-textarea wire:model="description" class="w-full" />
                                    @error('description')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 bg-indigo-50 p-4 rounded-lg">
                        <!-- Ingredients Selection -->
                        <h3 class="text-lg font-semibold">{{ __('Ingredients') }}</h3>
                        <div class="flex flex-wrap flex-col gap-4">
                            @foreach ($this->selectedIngredients as $ingredientId => $ingredientData)
                                @php
                                    $ingredient = $this->filteredIngredients->firstWhere('id', $ingredientId);
                                @endphp
                                @if ($ingredient)
                                    <div class="border p-4 rounded-lg relative border-blue-500">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium">{{ $ingredient->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $ingredient->type }}</p>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                <x-input type="number"
                                                    wire:model="selectedIngredients.{{ $ingredientId }}.quantity"
                                                    class="w-20 rounded border-gray-300" placeholder="Qty" />
                                                <select wire:model="selectedIngredients.{{ $ingredientId }}.unit"
                                                    class="mt-2 w-20 rounded border-gray-300">
                                                    <option value="g">g</option>
                                                    <option value="ml">ml</option>
                                                    <option value="units">units</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-span-1 bg-indigo-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Instructions') }}</h3>
                        <div class="space-y-2">
                            @foreach ($instructions as $index => $instruction)
                                <div class="flex gap-2 items-start">
                                    <span class="mt-2 text-sm font-semibold">{{ $index + 1 }}.</span>
                                    <div class="flex-1">
                                        <x-textarea wire:model="instructions.{{ $index }}" class="w-full"
                                            rows="2" />
                                    </div>
                                    <x-button type="button" wire:click="removeInstruction({{ $index }})"
                                        color="danger" class="mt-1">
                                        <span class="material-icons">delete</span>
                                    </x-button>
                                </div>
                            @endforeach
                            <x-button type="button" wire:click="addInstruction" color="secondary" class="mt-4">
                                <span class="material-icons mr-2">add</span>
                                {{ __('Add Step') }}
                            </x-button>
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <x-button type="button" color="alert" size="xs" wire:click="$set('showForm', false)">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button type="submit" color="primary" size="xs">
                        {{ $editingRecipeId ? __('Update Recipe') : __('Create Recipe') }}
                    </x-button>
                </div>
            </form>
        @endif

        <!-- Enhanced Recipe List -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">{{ __('Existing Recipes') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($this->recipes as $recipe)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        @if ($recipe->image)
                            <img src="{{ Storage::url($recipe->image) }}" alt="{{ $recipe->name }}"
                                class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">{{ $recipe->name }}</h4>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $recipe->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100' }}">
                                    {{ $recipe->is_featured ? __('Featured') : '' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($recipe->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <span class="material-icons text-sm">schedule</span>
                                    {{ $recipe->preparation_time }} {{ __('min') }}
                                </div>
                                <div class="flex gap-2">
                                    <x-button type="button" wire:click="duplicateRecipe({{ $recipe->id }})"
                                        color="info" size="sm">
                                        <span class="material-icons">content_copy</span>
                                    </x-button>
                                    <x-button type="button" wire:click="editRecipe({{ $recipe->id }})"
                                        color="warning" size="sm">
                                        <span class="material-icons">edit</span>
                                    </x-button>
                                    <x-button type="button" wire:click="deleteRecipe({{ $recipe->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this recipe?') }}"
                                        color="danger" size="sm">
                                        <span class="material-icons">delete</span>
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        {{ __('No recipes found.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
