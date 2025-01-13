<div>
    <div class="p-6 bg-gray-100 rounded-lg shadow-lg">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ __('Recipe Management') }}
            </h2>
            <x-button wire:click="createRecipe" primary>
                {{ __('Create New Recipe') }}
            </x-button>
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
        
        @if ($showForm)
            <div class="mb-8" x-data="{ step: 1 }">
                <div class="flex justify-center mb-6">
                    <div class="flex space-x-2">
                        @foreach (['Basic Info', 'Ingredients', 'Instructions'] as $stepName)
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center 
                                {{ $step == $loop->index + 1 ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $loop->index + 1 }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{-- Step 1: Basic Information --}}
                    <div x-show="$wire.step === 1">
                        <x-input-group>
                            <x-label for="{{ __('Recipe Name') }}"></x-label>
                            <x-input wire:model="name" id="name" type="text" />
                            <x-input-error for="name" />
                        </x-input-group>

                        <x-input-group>
                            <x-label for="{{ __('Description') }}"></x-label>
                            <x-textarea wire:model="description" id="description" />
                            <x-input-error for="description" />
                        </x-input-group>

                        <x-input-group>
                            <x-label for="{{ __('Recipe Type') }}"></x-label>
                            <select wire:model="type" id="type"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Select recipe type') }}</option>
                                @foreach ($this->recipeTypes as $recipeType)
                                    <option value="{{ $recipeType->value }}">{{ $recipeType->label() }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="type" />
                        </x-input-group>
                    </div>

                    {{-- Step 2: Ingredients --}}
                    <div x-show="$wire.step === 2">
                        <div class="space-y-4">
                            <div class="flex gap-4 mb-4">
                                <x-input wire:model.live="searchIngredient" type="search"
                                    placeholder="{{ __('Search ingredients...') }}" />
                                <select wire:model.live="selectedCategory"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">{{ __('Select ingredient category') }}</option>
                                    @foreach ($this->ingredientCategories as $ingredientCategory)
                                        <option value="{{ $ingredientCategory }}">{{ $ingredientCategory }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($this->filteredIngredients as $ingredient)
                                    <x-recipe.ingredient-card :ingredient="$ingredient" :selected="isset($selectedIngredients[$ingredient->id])"
                                        wire:click="toggleIngredient({{ $ingredient->id }})" />
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Instructions --}}
                    <div x-show="$wire.step === 3">
                        <div class="space-y-4">
                            @foreach ($instructions as $index => $instruction)
                                <div class="flex gap-2">
                                    <x-textarea wire:model="instructions.{{ $index }}"
                                        placeholder="{{ __('Add instruction step...') }}" />
                                    <x-button.icon wire:click="removeInstruction({{ $index }})" danger>
                                        <x-icon name="trash" class="w-5 h-5" />
                                    </x-button.icon>
                                </div>
                            @endforeach

                            <x-button wire:click="addInstruction" secondary class="mt-2">
                                {{ __('Add Step') }}
                            </x-button>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex justify-between mt-6">
                    <x-button wire:click="previousStep" x-show="$wire.step > 1" secondary>
                        {{ __('Previous') }}
                    </x-button>

                    <div class="ml-auto">
                        @if ($step < 3)
                            <x-button wire:click="nextStep" primary>
                                {{ __('Next') }}
                            </x-button>
                        @else
                            <x-button wire:click="saveRecipe" primary>
                                {{ __('Save Recipe') }}
                            </x-button>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- Recipe List --}}
            <div class="space-y-4">
                <div class="flex gap-4 mb-4">
                    <x-input wire:model.live="searchRecipe" type="search"
                        placeholder="{{ __('Search recipes...') }}" />
                    <select wire:model.live="selectedType" :options="$this - > recipeTypes" />
                </div>

                <x-table>
                    <x-slot name="thead">
                        <x-table.heading>{{ __('Name') }}</x-table.heading>
                        <x-table.heading>{{ __('Type') }}</x-table.heading>
                        <x-table.heading>{{ __('Ingredients') }}</x-table.heading>
                        <x-table.heading>{{ __('Actions') }}</x-table.heading>
                    </x-slot>

                    @foreach ($this->recipes as $recipe)
                        <x-table.row>
                            <x-table.cell>{{ $recipe->name }}</x-table.cell>
                            <x-table.cell>{{ $recipe->type }}</x-table.cell>
                            <x-table.cell>{{ $recipe->ingredients_count }}</x-table.cell>
                            <x-table.cell>
                                <div class="flex gap-2">
                                    <x-button.icon wire:click="editRecipe({{ $recipe->id }})" secondary>
                                        <x-icon name="pencil" class="w-5 h-5" />
                                    </x-button.icon>
                                    <x-button.icon wire:click="duplicateRecipe({{ $recipe->id }})" secondary>
                                        <x-icon name="duplicate" class="w-5 h-5" />
                                    </x-button.icon>
                                    <x-button.icon wire:click="deleteRecipe({{ $recipe->id }})" danger>
                                        <x-icon name="trash" class="w-5 h-5" />
                                    </x-button.icon>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-table>
            </div>
        @endif
    </div>
</div>
