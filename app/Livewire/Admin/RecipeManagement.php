<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Ingredient;
use Livewire\Component;
use Livewire\Attributes\Computed;

class RecipeManagement extends Component
{
    public $name;
    public $description;
    public $stock;
    public $instructions;
    public $availableIngredients;
    public $selectedIngredients;
    public $editingProductId;
    public $recipes;
    public $ingredients = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'stock' => 'required|in:300,500',
        'ingredients' => 'required|array|min:1',
        // 'ingredients.*.id' => 'required|exists:ingredients,id',
        // 'ingredients.*.stock' => 'required|numeric|min:0',
    ];

    #[Computed]
    public function recipes()
    {
        return Product::all();
    }

    // editProduct
    public function editProduct($id)
    {
        $this->editingProductId = $id;
        $recipe = Product::find($id);
        $this->name = $recipe->name;
        $this->stock = $recipe->stock;
        $this->ingredients = $recipe->ingredients->map(function ($ingredient) {
            return ['id' => $ingredient->id, 'stock' => $ingredient->stock];
        })->toArray();
    }

    // mount        

    public function mount()
    {
        $this->availableIngredients = Ingredient::all();
    }
    public function addIngredient()
    {
        $this->ingredients[] = ['id' => '', 'stock' => 0];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function saveProduct()
    {
        $this->validate();

        $recipe = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'stock' => $this->stock,
            'instructions' => $this->instructions,
        ]);

        foreach ($this->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], ['stock' => $ingredient['stock']]);
        }

        $this->reset();
        session()->flash('message', 'Product created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.recipe-management');
    }
}
