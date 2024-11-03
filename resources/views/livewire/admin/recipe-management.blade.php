<div>
    <div class="w-full py-16">

        <!-- Header with Filters -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Recipe Management') }}</h2>
                <p class="text-sm text-gray-600">{{ __('Create and manage product recipes') }}</p>
            </div>
            <div class="flex gap-4">
                <input type="text" wire:model.live="searchIngredient" class="rounded border-gray-300"
                    placeholder="{{ __('Search ingredients...') }}">
                <select wire:model.live="selectedType" class="rounded border-gray-300">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="fruit">{{ __('Fruits') }}</option>
                    <option value="liquid">{{ __('Liquids') }}</option>
                    <option value="coffee">{{ __('Coffee') }}</option>
                </select>
            </div>
        </div>

        <!-- Recipe Form -->
        @if ($showForm)
            <form wire:submit="saveRecipe" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-2 gap-6">
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
                <!-- Ingredients Selection -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Ingredients') }}</h3>
                    <div class="grid grid-cols-3 gap-4">

                        @foreach ($this->filteredIngredients as $ingredient)
                            <div
                                class="border p-4 rounded-lg relative {{ in_array($ingredient->id, collect($selectedIngredients)->pluck('id')->toArray()) ? 'border-blue-500' : '' }}">

                                <div class="flex justify-between items-start">

                                    <div>

                                        <h4 class="font-medium">{{ $ingredient->name }}</h4>

                                        <p class="text-sm text-gray-600">{{ $ingredient->type }}</p>

                                    </div>

                                    <div class="flex flex-col items-end">

                                        <input type="number"
                                            wire:model="selectedIngredients.{{ $ingredient->id }}.quantity"
                                            class="w-20 rounded border-gray-300" placeholder="Qty">

                                        <select wire:model="selectedIngredients.{{ $ingredient->id }}.unit"
                                            class="mt-2 w-20 rounded border-gray-300">

                                            <option value="g">g</option>

                                            <option value="ml">ml</option>

                                            <option value="units">units</option>

                                        </select>

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
                <!-- Cost and Nutritional Info -->
                <div class="grid grid-cols-2 gap-6">

                    <div class="border p-4 rounded-lg">

                        <h3 class="text-lg font-semibold mb-4">{{ __('Cost Breakdown') }}</h3>

                        <div class="space-y-2">

                            <p>{{ __('Total Cost') }}: {{ number_format($this->calculateRecipeCost(), 2) }} DH</p>

                            <p>{{ __('Suggested Price') }}: {{ number_format($this->calculateRecipeCost() * 1.5, 2) }}
                                DH
                            </p>

                        </div>
                    </div>

                    <div class="border p-4 rounded-lg">

                        <h3 class="text-lg font-semibold mb-4">{{ __('Nutritional Information') }}</h3>

                        @php $nutritionalInfo = $this->getNutritionalInfo(); @endphp

                        <div class="space-y-2">

                            <p>{{ __('Calories') }}: {{ number_format($nutritionalInfo['calories'], 2) }} kcal</p>

                            <p>{{ __('Protein') }}: {{ number_format($nutritionalInfo['protein'], 2) }} g</p>

                            <p>{{ __('Carbs') }}: {{ number_format($nutritionalInfo['carbs'], 2) }} g</p>

                            <p>{{ __('Fat') }}: {{ number_format($nutritionalInfo['fat'], 2) }} g</p>

                        </div>
                    </div>

                </div>
                <!-- Instructions -->
                <div>

                    <x-input-label for="instructions" :value="__('Preparation Instructions')" />

                    <div class="space-y-2">

                        @foreach ($instructions as $index => $instruction)
                            <div class="flex gap-2">
                                <input type="text" wire:model="instructions.{{ $index }}"
                                    class="w-full rounded border-gray-300">
                                <x-button type="button" wire:click="removeInstruction({{ $index }})"
                                    color="danger">
                                    <span class="material-icons text-red-500">delete</span>
                                </x-button>
                            </div>
                        @endforeach

                        <x-button type="button" wire:click="addInstruction" color="secondary">
                            <span class="material-icons text-blue-500">add</span>
                            {{ __('Add Step') }}
                        </x-button>
                    </div>

                </div>
                <!-- Actions -->
                <div class="flex justify-end gap-4">

                    <x-button type="button" variant="secondary" wire:click="$set('showForm', false)">

                        {{ __('Cancel') }}

                    </x-button>

                    <x-button type="submit">

                        {{ $editingProductId ? __('Update Recipe') : __('Create Recipe') }}

                    </x-button>

                </div>
            </form>
        @endif
        
        <!-- Recipe List -->
        <div class="mt-8">

            <h3 class="text-xl font-semibold mb-4">{{ __('Existing Recipes') }}</h3>

            <div class="grid grid-cols-3 gap-6">

                @foreach ($this->recipes as $recipe)
                    <div class="border rounded-lg overflow-hidden">

                        @if ($recipe->image)
                            <img src="{{ Storage::url($recipe->image) }}" alt="{{ $recipe->name }}"
                                class="w-full h-48 object-cover">
                        @endif

                        <div class="p-4">
                            <h4 class="font-semibold text-lg">{{ $recipe->name }}</h4>
                            <p class="text-sm text-gray-600 mb-4">{{ $recipe->description }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    <p>{{ __('Cost') }}: {{ $recipe->cost }}</p>
                                    <p>{{ __('Price') }}: {{ $recipe->price }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <x-button type="button" wire:click="duplicateRecipe({{ $recipe->id }})"
                                        color="info">
                                        <span class="material-icons text-blue-500">copy</span>
                                    </x-button>
                                    <x-button type="button" wire:click="editRecipe({{ $recipe->id }})"
                                        color="warning">
                                        <span class="material-icons text-yellow-500">edit</span>
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
