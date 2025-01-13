<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\RecipeType;
use App\Enums\UnitType;
use App\Models\Ingredient;
use App\Models\Recipe;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class RecipeManagement extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name;

    #[Validate('required|string')]
    public string $description;

    #[Validate('required|integer|min:0')]
    public int $preparation_time;

    #[Validate('required|string')]
    public string $type;

    public $is_featured = false;

    #[Validate('array')]
    public array $instructions = [];

    #[Validate('array|min:1')]
    public array $selectedIngredients = [];

    public $editingRecipeId = null;
    public string $searchIngredient = '';
    public string $selectedCategory = '';
    public bool $showForm = false;

    public string $searchRecipe = '';
    public ?string $selectedType = '';

    #[Computed]
    public function filteredIngredients()
    {
        return Ingredient::query()
            ->when($this->searchIngredient, fn ($q) =>
            $q->where('name', 'like', "%{$this->searchIngredient}%"))
            ->get();
    }

    public function addInstruction(): void
    {
        $this->instructions[] = '';
    }

    public function removeInstruction(int $index): void
    {
        unset($this->instructions[$index]);
        $this->instructions = array_values($this->instructions);
    }

    public function calculateNutritionalInfo(): array
    {
        return collect($this->selectedIngredients)->reduce(function ($carry, $ingredient) {
            $ingredientModel = Ingredient::find($ingredient['id']);
            $quantity = $ingredient['quantity'];

            $nutrition = $ingredientModel->calculateNutritionalInfo($quantity);

            return [
                'calories' => $carry['calories'] + $nutrition['calories'],
                'protein' => $carry['protein'] + $nutrition['protein'],
                'carbs' => $carry['carbs'] + $nutrition['carbs'],
                'fat' => $carry['fat'] + $nutrition['fat'],
            ];
        }, ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0]);
    }

    public function saveRecipe(): void
    {
        $this->validate();

        try {
            DB::transaction(function (): void {
                $recipe = Recipe::updateOrCreate(
                    ['id' => $this->editingRecipeId],
                    [
                        'name' => $this->name,
                        'description' => $this->description,
                        'preparation_time' => $this->preparation_time,
                        'type' => $this->type,
                        'is_featured' => $this->is_featured,
                        'instructions' => $this->instructions,
                    ]
                );

                $ingredientData = collect($this->selectedIngredients)->mapWithKeys(function ($ingredient) {
                    return [$ingredient['id'] => [
                        'quantity' => $ingredient['quantity'],
                        'unit' => $ingredient['unit'],
                        'preparation_notes' => $ingredient['preparation_notes'] ?? null,
                    ]];
                });

                $recipe->ingredients()->sync($ingredientData);

                $recipe->product()->updateOrCreate(
                    [],
                    [
                        'name' => $recipe->name,
                        'description' => $recipe->description,
                        'status' => true,
                        'category_id' => 1,
                    ]
                );

                $this->reset();
                session()->flash('success', __('Recipe saved successfully.'));
            });

            $this->showForm = false;
            $this->dispatch('recipe-saved');
            session()->flash('success', __('Recipe saved successfully.'));
        } catch (Exception $e) {
            logger()->error('Error saving recipe:', ['error' => $e->getMessage()]);
            session()->flash('error', __('Failed to save recipe. Please try again.'));
        }
    }

    #[Computed]
    public function recipes(): Collection
    {
        return Recipe::query()
            ->with(['ingredients', 'product'])
            ->when(
                $this->searchRecipe,
                fn ($query) =>
                $query->where('name', 'like', "%{$this->searchRecipe}%")
            )
            ->when(
                $this->selectedType,
                fn ($query) =>
                $query->where('type', $this->selectedType)
            )
            ->latest()
            ->get();
    }

    public function duplicateRecipe(int $recipeId): void
    {
        $original = Recipe::with('ingredients')->findOrFail($recipeId);
        $copy = $original->replicate();
        $copy->name = "{$original->name} (Copy)";
        $copy->save();

        $copy->ingredients()->sync(
            $original->ingredients->mapWithKeys(fn ($ingredient) => [
                $ingredient->id => [
                    'quantity' => $ingredient->pivot->quantity,
                    'unit' => $ingredient->pivot->unit,
                    'preparation_notes' => $ingredient->pivot->preparation_notes,
                ]
            ])->toArray()
        );

        session()->flash('success', __('Recipe duplicated successfully.'));
    }

    public function editRecipe(int $recipeId): void
    {
        $recipe = Recipe::with('ingredients')->findOrFail($recipeId);
        $this->editingRecipeId = $recipeId;
        $this->name = $recipe->name;
        $this->description = $recipe->description;
        $this->preparation_time = $recipe->preparation_time;
        $this->type = $recipe->type;
        $this->is_featured = $recipe->is_featured;
        $this->instructions = $recipe->instructions;

        $this->selectedIngredients = $recipe->ingredients->mapWithKeys(function ($ingredient) {
            return [$ingredient->id => [
                'id' => $ingredient->id,
                'quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit,
                'preparation_notes' => $ingredient->pivot->preparation_notes,
            ]];
        })->toArray();

        $this->showForm = true;
    }

    public function calculateCost(): float
    {
        return collect($this->selectedIngredients)->reduce(function ($totalCost, $ingredient) {
            $ingredientModel = Ingredient::find($ingredient['id']);
            return $totalCost + ($ingredient['quantity'] * $ingredientModel->cost);
        }, 0.0);
    }

    #[Computed]
    public function recipeTypes()
    {
        // dd(RecipeType::cases());
        return RecipeType::cases();
    }

    #[Computed]
    public function unitTypes(): array
    {
        return UnitType::cases();
    }

    public function createRecipe(): void
    {
        $this->reset([
            'editingRecipeId',
            'name',
            'description',
            'preparation_time',
            'type',
            'is_featured',
            'instructions',
            'selectedIngredients'
        ]);
        $this->showForm = true;
    }

    public function deleteRecipe(int $recipeId): void
    {
        try {
            $recipe = Recipe::findOrFail($recipeId);
            $recipe->delete();

            session()->flash('success', __('Recipe deleted successfully.'));
        } catch (Exception $e) {
            Log::error('Error deleting recipe:', ['error' => $e->getMessage()]);
            session()->flash('error', __('Failed to delete recipe. Please try again.'));
        }
    }
}
