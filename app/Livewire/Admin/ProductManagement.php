<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Products')]
class ProductManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $is_available = true;
    public $image;
    public $is_featured = false;
    public $editingProductId = null;
    public $selectedIngredients = [];
    public $category_filter = '';
    public $status_filter = '';
    public $showForm = false;
    public $showAnalytics = false;
    public $totalProducts = 0;
    public $inventoryValue = 0;
    public $activeCategories = 0;
    public $showRecipeForm = false; // New property for recipe form
    public $recipeId = null; // To hold the recipe ID for editing
    public $recipeInstructions = []; // To hold recipe instructions
    public $recipeNutritionalInfo = []; // To hold nutritional info for the recipe


    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'selectedIngredients' => 'array',
        'selectedIngredients.*.id' => 'exists:ingredients,id',
        'selectedIngredients.*.stock' => 'required|numeric|min:0',
        'recipeInstructions' => 'required|array|min:1', // Recipe instructions validation
    ];

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when(
                $this->search,
                fn ($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->category_id,
                fn ($query) =>
                $query->where('category_id', $this->category_id)
            )
            ->with(['category', 'ingredients'])
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function ingredients()
    {
        return Ingredient::all();
    }

    public function saveProduct(): void
    {
        $this->validate();

        $productData = [
            'name' => $this->name,
            'description' => $this->description,
            'slug' => Str::slug($this->name),
            'price' => $this->price,
            'category_id' => $this->category_id,
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
        ];

        if ($this->image) {
            $productData['image'] = $this->image->store('products', 'public');
        }

        if ($this->editingProductId) {
            $product = Product::find($this->editingProductId);
            $product->update($productData);
        } else {
            $product = Product::create($productData);
        }

        // Update ingredients
        $product->ingredients()->sync(collect($this->selectedIngredients)->mapWithKeys(fn ($ingredient) => [$ingredient['id'] => ['stock' => $ingredient['stock']]]));

        $this->reset();

        session()->flash('message', __('Product saved successfully.'));
    }

    public function toggleRecipeForm($productId): void
    {
        $this->showRecipeForm = ! $this->showRecipeForm;
        $this->recipeId = null; // Reset recipe ID
        $this->recipeInstructions = []; // Reset instructions

        if ($this->showRecipeForm) {
            $product = Product::with('recipe')->find($productId);
            if ($product->recipe) {
                $this->recipeId = $product->recipe->id;
                $this->recipeInstructions = $product->recipe->instructions;
                // Load other recipe data if needed
            }
        }
    }

    public function saveRecipe(): void
    {
        $this->validate([
            'recipeInstructions' => 'required|array|min:1',
        ]);

        $recipeData = [
            'name' => $this->name,
            'description' => $this->description,
            'instructions' => $this->recipeInstructions,
            'preparation_time' => 10, // Set a default or make it dynamic
            'type' => 'juice', // Set a default or make it dynamic
            'is_featured' => false, // Set a default or make it dynamic
            'nutritional_info' => [], // Calculate or set default
        ];

        if ($this->recipeId) {
            $recipe = Recipe::find($this->recipeId);
            $recipe->update($recipeData);
        } else {
            $recipe = Recipe::create($recipeData);
            // Link the recipe to the product
            $product = Product::find($this->editingProductId);
            $product->recipe()->associate($recipe);
            $product->save();
        }

        $this->reset();
        $this->showRecipeForm = false;
        session()->flash('message', __('Recipe saved successfully.'));
    }

    public function editProduct(Product $product): void
    {
        $this->showForm = true;
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->is_available = $product->is_available;
        $this->is_featured = $product->is_featured;

        $this->selectedIngredients = $product->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'stock' => $ingredient->pivot->stock,
            ];
        })->toArray();
    }

    public function updatedShowForm($value): void
    {
        if ( ! $value) {
            $this->reset();
        }
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
        session()->flash('message', __('Product deleted successfully.'));
    }

    public function updateProductAvailability(): void
    {
        $products = Product::with('ingredients')->get();
        foreach ($products as $product) {
            $isAvailable = $this->checkProductAvailability($product);
            if ($product->is_available !== $isAvailable) {
                $product->update(['is_available' => $isAvailable]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.product-management');
    }

    private function checkProductAvailability(Product $product): bool
    {
        foreach ($product->ingredients as $ingredient) {
            if ( ! $ingredient->hasEnoughStock($ingredient->pivot->stock)) {
                return false;
            }
        }
        return true;
    }
}
