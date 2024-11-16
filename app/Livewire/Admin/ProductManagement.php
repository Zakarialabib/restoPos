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
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Product Management')]
class ProductManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    #[Validate('required|max:255')]
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
    public $product = '';
    public $recipeInstructions = [];
    public $recipeNutritionalInfo = [];
    public $selectedSize;
    public $selectedUnit;
    public $sizePrices = [];

    public $prices = []; // Added to hold price data

    public $isCustomizable = false;
    public $allowedIngredients = [];
    public $basePrice = 0.00;
    public $customizationOptions = [];

    protected $rules = [
        'selectedIngredients.*.id' => 'exists:ingredients,id',
        'selectedIngredients.*.quantity' => 'required|numeric|min:0',
        'recipeInstructions.*' => 'required|string',
        'recipeInstructions.*.step' => 'required|numeric',
        'prices.*.price' => 'required|numeric|min:0', // Added validation for prices
        'isCustomizable' => 'boolean',
        'allowedIngredients' => 'array',
        'basePrice' => 'required_if:isCustomizable,true|numeric|min:0',
        'customizationOptions' => 'array',
    ];

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->category_id,
                fn($query) =>
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

        try {
            DB::beginTransaction();

            $productData = [
                'name' => $this->name,
                'description' => $this->description,
                'slug' => Str::slug($this->name),
                'category_id' => $this->category_id,
                'is_available' => $this->is_available,
                'is_featured' => $this->is_featured,
                'is_customizable' => $this->isCustomizable,
                'base_price' => $this->basePrice,
                'customization_options' => $this->customizationOptions,
            ];

            // Handle image upload
            if ($this->image && !is_string($this->image)) {
                $productData['image'] = $this->image->store('products', 'public');
            }

            // Create or update product
            if ($this->editingProductId) {
                $product = $this->editingProductId;
                $product->update($productData);
            } else {
                $product = Product::create($productData);
            }

            // Save size-based prices
            if (!empty($this->sizePrices)) {
                foreach ($this->sizePrices as $sizePrice) {
                    $product->addSizePrice(
                        $sizePrice['size'],
                        $sizePrice['unit'],
                        $sizePrice['cost'],
                        $sizePrice['price']
                    );
                }
            }

            // Save ingredients
            if (!empty($this->selectedIngredients)) {
                $ingredientData = collect($this->selectedIngredients)
                    ->filter(fn($ingredient) => !empty($ingredient['id']))
                    ->mapWithKeys(function ($ingredient) {
                        return [$ingredient['id'] => [
                            'quantity' => $ingredient['quantity'],
                            'unit' => $ingredient['unit'] ?? 'g'
                        ]];
                    })->toArray();
                
                $product->ingredients()->sync($ingredientData);
            }

            // Save recipe if instructions exist
            if (!empty($this->recipeInstructions)) {
                $recipeData = [
                    'name' => $this->name,
                    'description' => $this->description,
                    'instructions' => collect($this->recipeInstructions)->pluck('instruction')->toArray(),
                    'preparation_time' => 10,
                    'type' => 'juice',
                    'is_featured' => false,
                    'nutritional_info' => $product->calculateNutritionalInfo(),
                ];

                if ($product->recipe) {
                    $product->recipe->update($recipeData);
                } else {
                    $recipe = Recipe::create($recipeData);
                    $product->recipe()->associate($recipe);
                    $product->save();
                }
            }

            // If product is customizable, save allowed ingredients
            if ($this->isCustomizable && !empty($this->allowedIngredients)) {
                $product->allowedIngredients()->sync($this->allowedIngredients);
            }

            DB::commit();
            
            $this->reset();
            session()->flash('success', __('Product saved successfully.'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error saving product: ') . $e->getMessage());
        }
    }

    public function toggleRecipeForm($productId = null): void
    {
        $this->showRecipeForm = ! $this->showRecipeForm;
        $this->recipeId = null;
        $this->recipeInstructions = [
            [
                'step' => 1,
                'instruction' => ''
            ]
        ];

        if ($this->showRecipeForm) {
            if ($productId) {
                $product = Product::with('recipe')->find($productId);
                if ($product->recipe) {
                    $this->recipeId = $product->recipe->id;
                    $this->recipeInstructions = $product->recipe->instructions;
                }
            } else {
                $this->recipeId = null;
                $this->recipeInstructions = [
                    [
                        'step' => 1,
                        'instruction' => ''
                    ]
                ];
            }
        }
    }

    // add more recipe instructions
    public function addRecipeInstruction(): void
    {
        $this->recipeInstructions[] = [
            'step' => count($this->recipeInstructions) + 1,
            'instruction' => ''
        ];
    }

    // removeInstruction
    public function removeInstruction($index): void
    {
        unset($this->recipeInstructions[$index]);
        $this->recipeInstructions = array_values($this->recipeInstructions);
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
            $product = $this->editingProductId;
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
        $this->editingProductId = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->is_available = $product->is_available;
        $this->is_featured = $product->is_featured;
        $this->image = $product->image; // Store existing image path

        // Load size-based prices
        $this->sizePrices = $product->prices->map(function ($price) {
            return [
                'size' => $price->metadata['size'] ?? '',
                'unit' => $price->metadata['unit'] ?? '',
                'cost' => $price->cost,
                'price' => $price->price,
            ];
        })->toArray();

        // Load ingredients with proper structure
        $this->selectedIngredients = $product->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'quantity' => $ingredient->pivot->quantity,
                'unit' => $ingredient->pivot->unit ?? 'g',
            ];
        })->toArray();

        // Load recipe instructions if exists
        if ($product->recipe) {
            $this->recipeInstructions = collect($product->recipe->instructions)
                ->map(function ($instruction, $index) {
                    return [
                        'step' => $index + 1,
                        'instruction' => $instruction
                    ];
                })->toArray();
        }
    }

    public function updatedShowForm($value): void
    {
        if (! $value) {
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
        return Product::whereHas('ingredients', fn($query) => $query->where('stock', '<', 10))->get();
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

    public function updatedSelectedSize($size)
    {
        $this->updatePrice();
    }

    public function updatedSelectedUnit($unit)
    {
        $this->updatePrice();
    }

    protected function updatePrice(): void
    {
        if ($this->selectedSize && $this->selectedUnit) {
            $price = $this->editingProductId
                ->getPriceForSizeAndUnit($this->selectedSize, $this->selectedUnit);
            $this->price = $price ? $price->price : null;
        }
    }

    public function addSizePrice()
    {
        $this->sizePrices[] = [
            'size' => '',
            'unit' => 'g',
            'cost' => 0.00,
            'price' => 0.00
        ];
    }

    public function removeSizePrice($index)
    {
        unset($this->sizePrices[$index]);
        $this->sizePrices = array_values($this->sizePrices);
    }

    #[Computed]
    public function sizePrice()
    {
        if ($this->editingProductId) {
            return $this->editingProductId->sizePrice;
        }
        return collect($this->sizePrices);
    }

    //toggleAvailability
    public function toggleAvailability($productId)
    {
        $product = Product::find($productId);
        $product->is_available = ! $product->is_available;
        $product->save();
    }

    // toggleFeatured
    public function toggleFeatured($productId)
    {
        $product = Product::find($productId);
        $product->is_featured = ! $product->is_featured;
        $product->save();
    }

    // New method to calculate and return the nutritional information for the product
    public function getNutritionalInfo(): array
    {
        if ($this->editingProductId) {
            $product = $this->editingProductId;
            return $product->calculateNutritionalInfo();
        }
        return [];
    }

    // New method to get the total cost of ingredients for the product
    public function getTotalCost(): float
    {
        if ($this->editingProductId) {
            $product = $this->editingProductId;
            return $product->calculateCost();
        }
        return 0.0;
    }

    #[Computed]
    public function priceHistory()
    {
        if ($this->editingProductId) {
            return $this->editingProductId->prices()
                ->orderBy('date', 'desc')
                ->take(5)
                ->get();
        }
        return collect();
    }
}
