<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Exception;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Product Management')]
class ProductManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    #[Validate('required|string|max:255')]
    public $name = '';
    #[Validate('required|string')]
    public $description = '';
    #[Validate('required|numeric|min:0')]
    public $price = '';
    #[Validate('required|exists:categories,id')]
    public $category_id = '';
    #[Validate('required|boolean')]
    public $is_available = true;
    public $image;
    #[Validate('required|boolean')]
    public $is_featured = false;
    public $editingProductId = null;
    #[Validate('array')]
    public $selectedIngredients = [];
    public $category_filter = '';
    public $status_filter = '';
    public $showForm = false;
    public $showAnalytics = false;
    public $totalProducts = 0;
    public $inventoryValue = 0;
    public $activeCategories = 0;
    public $showRecipeForm = false;
    public $recipeId = null;
    public $recipeInstructions = [];
    public $recipeNutritionalInfo = [];

    protected $rules = [
        'selectedIngredients.*.id' => 'exists:ingredients,id',
        'selectedIngredients.*.quantity' => 'required|numeric|min:0',
        'recipeInstructions' => 'required|array|min:1',
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

        $product->ingredients()->sync(collect($this->selectedIngredients)->mapWithKeys(fn ($ingredient) => [$ingredient['id'] => ['quantity' => $ingredient['quantity']]]));

        $this->reset();

        session()->flash('success', __('Product saved successfully.'));
    }

    public function toggleRecipeForm($productId): void
    {
        $this->showRecipeForm = ! $this->showRecipeForm;
        $this->recipeId = null;
        $this->recipeInstructions = [];

        if ($this->showRecipeForm) {
            $product = Product::with('recipe')->find($productId);
            if ($product->recipe) {
                $this->recipeId = $product->recipe->id;
                $this->recipeInstructions = $product->recipe->instructions;
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
            'preparation_time' => 10,
            'type' => 'juice',
            'is_featured' => false,
            'nutritional_info' => [],
        ];

        if ($this->recipeId) {
            $recipe = Recipe::find($this->recipeId);
            $recipe->update($recipeData);
        } else {
            $recipe = Recipe::create($recipeData);
            $product = Product::find($this->editingProductId);
            $product->recipe()->associate($recipe);
            $product->save();
        }

        $this->reset();
        $this->showRecipeForm = false;
        session()->flash('success', __('Recipe saved successfully.'));
    }

    public function addProduct(): void
    {
        $this->reset();
        $this->showForm = true;
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
                'quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit,
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
        session()->flash('success', __('Product deleted successfully.'));
    }

    public function updateProductAvailability(): void
    {
        $products = Product::with('ingredients')->get();
        foreach ($products as $product) {
            $isAvailable = $product->isProductAvailable(1);
            if ($product->is_available !== $isAvailable) {
                $product->update(['is_available' => $isAvailable]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.product-management');
    }

    public function updateProductStock(Product $product, $quantity, $reason = 'Manual Update'): void
    {
        try {
            $product->consumeIngredients($quantity);
            $this->dispatch('stock-updated');
            session()->flash('success', __('Stock updated successfully'));
        } catch (Exception $e) {
            session()->flash('error', __('Error updating stock'));
        }
    }

    public function addIngredientField(): void
    {
        $this->selectedIngredients[] = ['id' => null, 'quantity' => null, 'unit' => null];
    }

    public function calculateProductCost(Product $product): float
    {
        return $product->calculateCost();
    }

    public function updateProductNutritionalInfo(Product $product): void
    {
        $product->update([
            'nutritional_info' => $product->calculateNutritionalInfo()
        ]);
    }

    #[Computed]
    public function lowStockProducts()
    {
        return Product::whereHas('ingredients', fn ($query) => $query->where('stock', '<', 10))->get();
    }

    #[Computed]
    public function productAnalytics()
    {
        return [
            'total_products' => Product::count(),
            // total_value
            'total_value' => Product::sum('price'),
            // active_categories
            'active_categories' => Category::where('is_active', true)->count(),
        ];
    }
}
