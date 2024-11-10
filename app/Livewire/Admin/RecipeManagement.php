<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class RecipeManagement extends Component
{
    use WithFileUploads;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string')]
    public $description;

    #[Rule('required|integer|min:0')]
    public $preparation_time;

    #[Rule('required|string')]
    public $type;

    #[Rule('boolean')]
    public $is_featured = false;

    #[Rule('array')]
    public $instructions = [];

    #[Rule('array|min:1')]
    public $selectedIngredients = [];

    public $editingRecipeId;
    public $searchIngredient = '';
    public $selectedCategory = '';
    public $showForm = false;

    // Add computed property for filtered ingredients
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

    public function removeInstruction($index): void
    {
        unset($this->instructions[$index]);
        $this->instructions = array_values($this->instructions);
    }

    public function calculateNutritionalInfo(): array
    {
        return collect($this->selectedIngredients)->reduce(function ($carry, $ingredient) {
            $ingredientModel = Ingredient::find($ingredient['id']);
            $quantity = $ingredient['quantity'];

            return [
                'calories' => $carry['calories'] + ($ingredientModel->nutritional_info['calories'] * $quantity / 100),
                'protein' => $carry['protein'] + ($ingredientModel->nutritional_info['protein'] * $quantity / 100),
                'carbs' => $carry['carbs'] + ($ingredientModel->nutritional_info['carbs'] * $quantity / 100),
                'fat' => $carry['fat'] + ($ingredientModel->nutritional_info['fat'] * $quantity / 100),
            ];
        }, ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0]);
    }

    public function saveRecipe(): void
    {
        $this->validate();

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
                    'nutritional_info' => $this->calculateNutritionalInfo(),
                ]
            );

            // Sync ingredients with quantities and units
            $ingredientData = collect($this->selectedIngredients)->mapWithKeys(function ($ingredient) {
                return [$ingredient['id'] => [
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'preparation_notes' => $ingredient['preparation_notes'] ?? null,
                ]];
            });

            $recipe->ingredients()->sync($ingredientData);

            // Create corresponding product
            $recipe->product()->updateOrCreate(
                [],
                [
                    'name' => $recipe->name,
                    'description' => $recipe->description,
                    'is_available' => true,
                    'category_id' => 1, // Set appropriate category
                ]
            );

            $this->reset();
            $this->dispatch('recipe-saved');
        });
    }

    #[Computed]
    public function recipes()
    {
        return Recipe::with(['ingredients', 'product'])->get();
    }

    public function duplicateRecipe($recipeId): void
    {
        $original = Recipe::with('ingredients')->findOrFail($recipeId);
        $copy = $original->replicate();
        $copy->name = "{$original->name} (Copy)";
        $copy->save();

        $copy->ingredients()->sync(
            $original->ingredients()->withPivot('quantity', 'unit', 'preparation_notes')->get()
        );

        $this->dispatch('recipe-duplicated');
    }

    public function editRecipe($recipeId): void
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

    public function calculateCost()
    {
        $totalCost = 0;
        foreach ($this->selectedIngredients as $ingredient) {
            $totalCost += $ingredient['quantity'] * $ingredient['price'];
        }
        return $totalCost;
    }
}
