<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\IngredientType;
use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\ComposableProductService;
use App\Services\IngredientCategoryService;
use App\Support\HasAdvancedFilter;
use App\Traits\ComposableComponent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

// Import the calculator service
use App\Services\ComposablePriceCalculator;

#[Layout('layouts.guest')]
class ComposableProductIndex extends Component
{
    use ComposableComponent;
    use HasAdvancedFilter;
    use WithPagination;

    #[Locked]
    public string $productType;
    #[Locked]
    public string $selectedCategorySlug = '';
    #[Locked]
    public $selectedCategory = null;

    #[Locked]
    public array $selectedIngredients = [];
    #[Locked]
    public ?string $selectedBase = null;
    #[Locked]
    public ?string $selectedSugar = null;

    public $order;
    #[Locked]
    public string $customProductName = '';
    #[Locked]
    public bool $loading = false;
    #[Locked]
    public ?string $error = null;
    #[Locked]
    public array $popularCombinations = [];
    #[Locked]
    public bool $showCartDrawer = false;
    #[Locked]
    public array $layers = [];
    #[Locked]
    public bool $isEditing = false;

    #[Locked]
    public array $selectedLayers = [];

    #[Locked]
    public int $currentLayer = 1;

    #[Locked]
    public array $layerTypes = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'selectedIngredients' => 'required|array|min:1',
        'selectedBase' => 'required_if:configuration.has_base,true',
        'selectedSugar' => 'required_if:configuration.has_sugar,true',
        'selectedSize' => 'required_if:configuration.has_size,true',
    ];

    protected IngredientCategoryService $categoryService;

    // Add property for the calculator service
    protected ComposablePriceCalculator $priceCalculator;

    public function scopeWithCategory(Builder $query, $categoryId): Builder
    {
        return $query->whereHas('category', function ($q) use ($categoryId): void {
            $q->where('id', $categoryId);
        });
    }

    public function scopeWithIngredients(Builder $query, array $ingredientIds): Builder
    {
        return $query->whereHas('ingredients', function ($q) use ($ingredientIds): void {
            $q->whereIn('ingredients.id', $ingredientIds);
        }, '=', count($ingredientIds));
    }

    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    public function scopeWithPreparationSteps(Builder $query, int $stepType): Builder
    {
        return $query->whereJsonContains('preparation_instructions->step_type', $stepType);
    }

    public function boot(IngredientCategoryService $categoryService, ComposablePriceCalculator $priceCalculator): void
    {
        $this->categoryService = $categoryService;
        // Inject the calculator service via method injection
        $this->priceCalculator = $priceCalculator;
    }

    public function mount(string $productType): void
    {
        $this->productType = $productType;
        $this->cart = session('cart.items', []);

        // Find the category based on product type
        $category = Category::where('slug', $this->productType)
            ->where('is_composable', true)
            ->with('composableConfiguration')
            ->firstOrFail();

        $this->selectedCategorySlug = $category->slug;
        $this->selectedCategory = $category;

        // Set default values based on product type
        if ('juices' === $productType) {
            if (empty($this->selectedSize)) {
                $this->selectedSize = 'medium';
            }

            if (empty($this->selectedSugar)) {
                $this->selectedSugar = 'none';
            }

            // Load popular combinations for juices
            $this->loadPopularCombinations();
        }

        // Check if we're editing an existing composable product
        $editingComposable = session('editing_composable');
        if ($editingComposable && isset($editingComposable['item'])) {
            $this->loadComposableForEditing($editingComposable['item']);
        }

        // Initial price calculation on mount if editing
        if ($this->isEditing) {
            $this->updateTotalPrice();
        }
    }

    public function toggleIngredient($ingredientId): void
    {
        if (in_array($ingredientId, $this->selectedIngredients)) {
            $this->selectedIngredients = array_diff($this->selectedIngredients, [$ingredientId]);
        } else {
            if ($this->configuration && count($this->selectedIngredients) >= $this->configuration->max_ingredients) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => __('Maximum number of ingredients reached (:max)', ['max' => $this->configuration->max_ingredients])
                ]);
                return;
            }
            $this->selectedIngredients[] = $ingredientId;
        }
        // Call the helper method to update price
        $this->updateTotalPrice();
    }

    public function selectBase(?string $baseId): void
    {
        $this->selectedBase = $baseId;
        // Call the helper method to update price
        $this->updateTotalPrice();
    }

    public function selectSugar(?string $sugarId): void
    {
        $this->selectedSugar = $sugarId;
        // Call the helper method to update price
        $this->updateTotalPrice();
    }

    public function selectSize(?string $size): void
    {
        // Ensure a size is always selected if required by config
        if ($this->configuration?->has_size && empty($size)) {
             $this->error = __('Please select a size.');
             $this->dispatch('notify', ['type' => 'error', 'message' => $this->error]);
             return;
        }
        $this->selectedSize = $size;
        // Call the helper method to update price
        $this->updateTotalPrice();
    }

    public function toggleAddon(string $addonId): void // Changed type hint to string to match DB IDs
    {
        if (in_array($addonId, $this->selectedAddons)) {
            $this->selectedAddons = array_diff($this->selectedAddons, [$addonId]);
        } else {
            $this->selectedAddons[] = $addonId;
        }
         // Call the helper method to update price
        $this->updateTotalPrice();
    }

    public function updateQuantity(int $cartIndex, int $quantity): void
    {
        if ($quantity < 1) {
            $quantity = 1;
        }

        if (isset($this->cart[$cartIndex])) {
            $this->cart[$cartIndex]['quantity'] = $quantity;
            $this->cart[$cartIndex]['total'] = $this->cart[$cartIndex]['price'] * $quantity;
            session()->put('cart', $this->cart);
        }
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        session()->put('cart', $this->cart);
        session()->flash('success', __('Item removed from cart'));
    }

    public function addToCart(): void
    {
        // Ensure price is calculated before adding to cart
        $this->updateTotalPrice();

        // Check if there was a calculation error
        if ($this->error) {
             $this->dispatch('notify', ['type' => 'error', 'message' => __('Cannot add to cart due to a configuration error: :error', ['error' => $this->error])]);
             return;
        }
         if ($this->totalPrice <= 0 && count($this->selectedIngredients) > 0) { // Check for invalid price
             $this->dispatch('notify', ['type' => 'error', 'message' => __('Calculated price is invalid. Please review your selections.')]);
             return;
        }

        try {
            $this->loading = true;

            if ( ! $this->configuration) {
                throw new Exception(__('Invalid category selected'));
            }

            // Validate selections based on configuration
            if (count($this->selectedIngredients) < $this->configuration->min_ingredients) {
                throw new Exception(__('Please select at least :min ingredients', ['min' => $this->configuration->min_ingredients]));
            }

            if ($this->configuration->has_base && ! $this->selectedBase) {
                throw new Exception(__('Please select a base'));
            }

            if ($this->configuration->has_size && ! $this->selectedSize) {
                throw new Exception(__('Please select a size'));
            }

            // Use the calculated totalPrice from the property
            $price = $this->totalPrice;

            // Get ingredient details
            $ingredientDetails = Ingredient::whereIn('id', $this->selectedIngredients)
                ->select('id', 'name', 'image')
                ->get()
                ->map(fn ($ingredient) => [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'image' => $ingredient->image,
                ])
                ->toArray();

            // Generate a name for the custom product
            $name = $this->customProductName ?: $this->generateCustomName();

            // Create new cart item
            $newItem = [
                'id' => 'custom_' . uniqid(),
                'name' => $name,
                'product_type' => $this->productType,
                'category' => $this->selectedCategory->name,
                'ingredients' => $ingredientDetails,
                'base' => $this->selectedBase,
                'sugar' => $this->selectedSugar,
                'size' => $this->selectedSize,
                'addons' => $this->selectedAddons,
                'price' => $price,
                'quantity' => 1,
                'total' => $price,
                'is_composable' => true,
                'image' => null,
                'addedAt' => now()->toIso8601String()
            ];

            // If this is a layered product, add layer information
            if ( ! empty($this->layers)) {
                $newItem['layers'] = $this->layers;
            }

            // Check if we're editing an existing item
            $editingComposable = session('editing_composable');
            if ($editingComposable) {
                // Use the CartDrawer component to update the item
                $this->dispatch('addComposableToCart', $newItem);
            } else {
                // Update session cart
                $cart = session('cart', ['items' => []]);
                $cart['items'][] = $newItem;
                session(['cart' => $cart]);

                // Update local cart
                $this->cart = $cart['items'];

                // Reset selections and show success message
                $this->resetSelections();
                $this->showSuccess = true;
                $this->error = null;

                // Dispatch event to update CartDrawer
                $this->dispatch('cartUpdated');
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => __('Custom :product added to cart!', ['product' => $this->productType])
                ]);
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);

            Log::error("Failed to add {$this->productType} to cart", [
                'error' => $e->getMessage(),
                'user' => Auth::id(),
                'selections' => [
                    'ingredients' => $this->selectedIngredients,
                    'base' => $this->selectedBase,
                    'sugar' => $this->selectedSugar,
                    'size' => $this->selectedSize,
                    'addons' => $this->selectedAddons,
                ]
            ]);
        } finally {
            $this->loading = false;
        }
    }

    public function placeOrder(): void
    {
        // Ensure latest price is considered if needed, though cart items should have fixed price
        // $this->updateTotalPrice(); // Might not be needed if cart price is fixed on 'Add'

        try {
            $this->loading = true;

            if (empty($this->cart)) {
                throw new Exception(__('Cart is empty'));
            }

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => OrderStatus::Pending,
                'total_amount' => $this->cartTotal,
            ]);

            foreach ($this->cart as $item) {
                $composition = [
                    'product_type' => $this->productType,
                    'ingredients' => $item['ingredients'],
                    'base' => $item['base'],
                    'sugar' => $item['sugar'],
                    'size' => $item['size'],
                    'addons' => $item['addons'],
                ];

                // Add layers if present
                if (isset($item['layers'])) {
                    $composition['layers'] = $item['layers'];
                }

                $orderItem = $order->items()->create([
                    // 'name' => $item['name'], // Use name from cart item
                    'product_id' => null, // Or link to a base 'composable product' ID if applicable
                    'quantity' => $item['quantity'],
                    // Use 'price' from the cart item, which was calculated on 'Add to Cart'
                    'price' => $item['price'],
                    // 'total_price' => $item['total'], // total_amount might be better? check OrderItem fillable
                    'total_amount' => $item['total'],
                    'details' => $composition, // Save composition details
                    // Add cost if calculated and needed
                    // 'cost' => $calculated_cost_for_item,
                ]);

                // Consume ingredients logic... needs refinement for quantities based on size/recipe
                // --- IMPORTANT: Stock Decrement Logic Needs Review ---
                // The current logic `$ingredientModel->decrementStock($item['quantity'])` is likely incorrect.
                // It decreases by the number of *final products* ordered, not the *amount* of ingredient used.
                // This needs recalculation based on the item's composition (ingredients, size) and potentially recipes/conversion rates.
                // This complex logic might belong in a dedicated InventoryService method called here.
                // Example placeholder:
                // $this->inventoryService->deductIngredientsForOrderItem($orderItem);

                // foreach ($item['ingredients'] as $ingredientInfo) {
                //     $ingredientId = is_array($ingredientInfo) ? $ingredientInfo['id'] : $ingredientInfo;
                //     $ingredientModel = Ingredient::find($ingredientId);
                //     if ($ingredientModel) {
                //         // FIXME: Calculate actual quantity needed based on size/recipe
                //         $quantityToDecrement = $this->calculateIngredientQuantityNeeded($ingredientModel, $item['size'], $item['quantity']);
                //         if ($quantityToDecrement > 0) {
                //              // Use a dedicated service method for safety
                //             // $this->inventoryService->decrementStock($ingredientModel, $quantityToDecrement, 'order_fulfillment', $orderItem->id);
                //             // Or direct call (less safe):
                //             // $ingredientModel->decrement('stock_quantity', $quantityToDecrement);
                //         }
                //     }
                // }
                 // Placeholder for demonstrating the issue - DO NOT USE IN PRODUCTION
                 Log::warning('Stock decrement logic needs implementation based on actual ingredient usage per item/size.');
                 // --- End Stock Decrement Review ---

            }

            DB::commit();

            // Clear cart
            session()->forget('cart');
            $this->cart = [];
            $this->order = $order;
            $this->error = null;

            // Show success message
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => __('Order placed successfully!')
            ]);

            // Dispatch event to update CartDrawer
            $this->dispatch('cartUpdated');
            $this->dispatch('order-placed');
        } catch (Exception $e) {
            DB::rollBack();
            $this->error = $e->getMessage();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);

            Log::error('Failed to place order', [
                'error' => $e->getMessage(),
                'user' => Auth::id(),
                'cart' => $this->cart
            ]);
        } finally {
            $this->loading = false;
        }
    }

    public function close(): void
    {
        $this->showSuccess = false;
        $this->resetSelections();
        session()->forget('cart');
    }

    #[Computed]
    public function cartTotal(): float
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }



    #[Computed]
    public function availableCategories()
    {
        return Category::query()
            ->where('is_composable', true)
            ->get();
    }

    #[Computed]
    public function composableCategories(): Collection
    {
        return $this->categoryService->getComposableCategories();
    }

    #[Computed]
    public function selectedCategory()
    {
        if ($this->selectedCategory) {
            return $this->selectedCategory;
        }

        if ( ! $this->selectedCategorySlug) {
            return null;
        }

        $category = Category::where('slug', $this->selectedCategorySlug)
            ->with(['composableConfiguration']) // Eager load the configuration
            ->first();

        $this->selectedCategory = $category;
        return $category;
    }

    #[Computed]
    public function configuration()
    {
        return $this->selectedCategory?->composableConfiguration;
    }

    public function updatedSelectedCategorySlug(): void
    {
        $this->resetSelections();
    }

    #[Computed]
    public function steps(): array
    {
        if ( ! $this->configuration) {
            return [];
        }

        $steps = [__('Select Ingredients')];

        if ($this->configuration->has_base) {
            $steps[] = __('Base');
        }
        if ($this->configuration->has_sugar) {
            $steps[] = __('Sugar');
        }
        if ($this->configuration->has_addons) {
            $steps[] = __('Add-ons');
        }
        if ($this->configuration->has_size) {
            $steps[] = __('Size');
        }

        return $steps;
    }

    #[Computed]
    public function availableIngredients()
    {
        if ( ! $this->selectedCategory) {
            return collect();
        }

        $query = Ingredient::query()
            ->where('status', true)
            ->where(function ($query): void {
                $query->where('category_id', $this->selectedCategory->id)
                    ->orWhereIn('type', [
                        IngredientType::BASE->value,
                        IngredientType::CONDIMENT->value,
                        IngredientType::FRUIT->value,
                        IngredientType::PROTEIN->value,
                        IngredientType::VEGETABLE->value,
                    ]);
            })
            ->with(['prices', 'category']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->get();
    }

    #[Computed]
    public function availableBases()
    {
        if ( ! $this->selectedCategory || ! $this->configuration?->has_base) {
            return collect();
        }

        return $this->categoryService->getIngredientsForComposable(
            $this->selectedCategory,
            IngredientType::BASE->value
        );
    }

    #[Computed]
    public function availableSugars()
    {
        if ( ! $this->selectedCategory || ! $this->configuration?->has_sugar) {
            return collect();
        }

        return Ingredient::query()
            ->where('category_id', $this->selectedCategory->id)
            ->where('type', IngredientType::SUGAR)
            ->with('prices') // Eager load prices
            ->get();
    }

    #[Computed]
    public function availableAddons()
    {
        if ( ! $this->selectedCategory || ! $this->configuration?->has_addons) {
            return collect();
        }

        return Ingredient::query()
            ->where('category_id', $this->selectedCategory->id)
            ->where('type', IngredientType::CONDIMENT)
            ->with('prices') // Eager load prices
            ->get();
    }

    #[Computed]
    public function availableSizes(): array
    {
        return $this->configuration?->sizes ?? [];
    }

    // Helper method to update total price using the service
    protected function updateTotalPrice(): void
    {
        // Ensure configuration is loaded
        if (!$this->configuration) {
            $this->totalPrice = 0.0;
            // Optionally log a warning or show a user-friendly error
            Log::warning('ComposableProductIndex: Configuration not loaded, cannot calculate price.');
            $this->error = __('Configuration error, cannot calculate price.');
            return;
        }

        try {
            // Reset previous error
            $this->error = null;
            // Call the service's calculate method
            $this->totalPrice = $this->priceCalculator->calculate(
                $this->selectedIngredients,
                $this->selectedBase,
                $this->selectedAddons,
                $this->selectedSize ?? 'medium', // Provide default if needed, ensure it's always set
                $this->selectedSugar,
                $this->configuration // Pass the loaded configuration object
            );
        } catch (\InvalidArgumentException $e) {
            // Handle specific validation errors from the calculator
            $this->totalPrice = 0.0; // Set price to 0 on error
            $this->error = $e->getMessage(); // Display error to user
            $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
            Log::warning('ComposablePriceCalculator Validation Error: ' . $e->getMessage(), [
                'ingredients' => $this->selectedIngredients,
                'base' => $this->selectedBase,
                'addons' => $this->selectedAddons,
                'size' => $this->selectedSize,
                'sugar' => $this->selectedSugar,
            ]);
        } catch (\Exception $e) {
            // Handle generic errors during calculation
            $this->totalPrice = 0.0; // Set price to 0 on error
            $this->error = __('Error calculating price. Please try again.'); // Generic error for user
            $this->dispatch('notify', ['type' => 'error', 'message' => $this->error]);
            // Log the detailed error internally
            Log::error('ComposablePriceCalculator General Error: ' . $e->getMessage(), [
                 'exception' => $e, // Include full exception if helpful
                 'ingredients' => $this->selectedIngredients,
                 'base' => $this->selectedBase,
                 'addons' => $this->selectedAddons,
                 'size' => $this->selectedSize,
                 'sugar' => $this->selectedSugar,
            ]);
        }
    }

    public function addLayer(string $ingredientId, string $type): void
    {
        $config = config('composable.juice.layers');

        // Check if we've reached max layers
        if (count($this->selectedLayers) >= $config['max_layers']) {
            session()->flash('error', __('Maximum number of layers reached'));
            return;
        }

        // Check if we've reached max layers for this type
        $typeCount = collect($this->selectedLayers)
            ->where('type', $type)
            ->count();

        if ($typeCount >= $config['layer_types'][$type]['max_per_drink']) {
            session()->flash('error', __('Maximum number of :type layers reached', ['type' => $type]));
            return;
        }

        $this->selectedLayers[] = [
            'ingredient_id' => $ingredientId,
            'type' => $type,
            'position' => count($this->selectedLayers) + 1
        ];

        $this->currentLayer++;
        // Recalculate price if layers affect it (using appropriate service method)
        // $this->totalPrice = $this->calculateLayeredPrice();
        $this->updateTotalPrice(); // Or use specific layered calculation if needed
    }

    public function removeLayer(int $index): void
    {
        unset($this->selectedLayers[$index]);
        $this->selectedLayers = array_values($this->selectedLayers);
        $this->currentLayer = count($this->selectedLayers) + 1;
        // Recalculate price
        // $this->totalPrice = $this->calculateLayeredPrice();
        $this->updateTotalPrice(); // Or use specific layered calculation if needed
    }

    public function moveLayer(int $from, int $to): void
    {
        if ($to >= 0 && $to < count($this->selectedLayers)) {
            $layer = $this->selectedLayers[$from];
            array_splice($this->selectedLayers, $from, 1);
            array_splice($this->selectedLayers, $to, 0, [$layer]);

            // Update positions
            foreach ($this->selectedLayers as $index => $layer) {
                $this->selectedLayers[$index]['position'] = $index + 1;
            }

            // Recalculate price
            // $this->totalPrice = $this->calculateLayeredPrice();
             $this->updateTotalPrice(); // Or use specific layered calculation if needed
        }
    }

    #[Computed]
    public function availableLayerTypes(): array
    {
        return config('composable.juice.layers.layer_types', []);
    }

    #[Computed]
    public function layerDistribution(): array
    {
        if (empty($this->selectedLayers)) {
            return [];
        }

        return app(ComposableProductService::class)
            ->calculateLayerDistribution(
                count($this->selectedLayers),
                $this->selectedSize ?? 'medium'
            );
    }

    #[Computed]
    public function suggestedCombinations(): array
    {
        return app(ComposableProductService::class)
            ->suggestLayerCombinations(
                collect($this->selectedLayers)->pluck('ingredient_id')->toArray()
            );
    }


    public function render()
    {
        return view('livewire.composable-product-index');
    }

    protected function getFilterSteps(): array
    {
        return config('composable.filter_steps') ?? [
            'category_selection',
            'ingredient_filtering',
            'price_calibration',
            'workflow_validation'
        ];
    }

    /**
     * Load popular combinations for the current product type
     */
    protected function loadPopularCombinations(): void
    {
        $this->popularCombinations = Cache::remember(
            "popular_{$this->productType}_combinations",
            now()->addHour(),
            function () {
                return OrderItem::query()
                    ->where('is_composable', true)
                    ->whereJsonContains('composition->product_type', $this->productType)
                    ->select('name', 'composition', DB::raw('COUNT(*) as order_count'))
                    ->groupBy('name', 'composition')
                    ->orderByDesc('order_count')
                    ->limit(6)
                    ->get()
                    ->map(function ($item) {
                        $composition = json_decode($item->composition, true);
                        return [
                            'name' => $item->name,
                            'ingredients' => $composition['ingredients'] ?? [],
                            'base' => $composition['base'] ?? '',
                            'sugar' => $composition['sugar'] ?? '',
                            'size' => $composition['size'] ?? '',
                            'addons' => $composition['addons'] ?? [],
                            'order_count' => $item->order_count,
                        ];
                    });
            }
        );
    }

    /**
     * Load a composable product for editing
     */
    protected function loadComposableForEditing(array $item): void
    {
        // Set the selected ingredients
        if (isset($item['ingredients'])) {
            $this->selectedIngredients = collect($item['ingredients'])
                ->pluck('id')
                ->filter()
                ->toArray();
        }

        // Set the selected base
        if (isset($item['base'])) {
            $this->selectedBase = $item['base'];
        }

        // Set the selected sugar
        if (isset($item['sugar'])) {
            $this->selectedSugar = $item['sugar'];
        }

        // Set the selected size
        if (isset($item['size'])) {
            $this->selectedSize = $item['size'];
        }

        // Set the selected addons
        if (isset($item['addons'])) {
            $this->selectedAddons = $item['addons'];
        }

        // Set the custom product name
        if (isset($item['name'])) {
            $this->customProductName = $item['name'];
        }

        // Calculate the total price using the service after loading selections
        $this->updateTotalPrice();

        // Set the editing mode
        $this->isEditing = true;

        // Show a notification
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => __('Editing product: :name', ['name' => $item['name']])
        ]);
    }

    /**
     * Generate a custom name for the product based on selected ingredients
     */
    protected function generateCustomName(): string
    {
        $ingredients = Ingredient::whereIn('id', $this->selectedIngredients)
            ->take(3)
            ->pluck('name')
            ->join(', ');

        return __('Custom :product', ['product' => ucfirst($this->productType)]) . " ({$ingredients})";
    }

    protected function resetSelections(): void
    {
        $this->reset([
            'selectedIngredients',
            'selectedBase',
            'selectedSugar',
            'selectedAddons',
            'selectedSize',
            'totalPrice',
            'search',
        ]);
        $this->step = 1;
    }

    // Remove calculateLayeredPrice if its logic is now covered by the main calculator or a specific service method
    // protected function calculateLayeredPrice(): float
    // {
    //     // ... old logic ...
    // }

    // --- Helper function placeholder for stock calculation ---
    // protected function calculateIngredientQuantityNeeded(Ingredient $ingredient, string $size, int $productQuantity): float
    // {
    //     // FIXME: Implement actual logic based on recipe/standard usage per size
    //     // Example: return $standardUsagePerML * $this->configuration->getSizeCapacity($size) * $productQuantity;
    //     return 0.1 * $productQuantity; // Placeholder: 0.1 units per product ordered
    // }
}
