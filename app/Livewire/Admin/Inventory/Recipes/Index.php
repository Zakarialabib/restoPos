<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Inventory\Recipes;

use App\Enums\RecipeType;
use App\Enums\UnitType;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Recipe Management')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $recipe;
    public $showOptimizationModal = false;
    public $showSeasonalAlternativesModal = false;
    public $showPromotionsModal = false;
    public $showReportingModal = false;
    public $selectedIngredient;
    public $searchTerm = '';
    public $filters = [
        'type' => '',
        'status' => '',
        'cost_min' => null,
        'cost_max' => null,
    ];

    public $showForm = false;
    public $editMode = false;
    public $step = 1;
    public $recipeData = [
        'name' => '',
        'description' => '',
        'type' => '',
        'preparation_time' => null,
        'cooking_time' => null,
        'serving_size' => null,
        'difficulty_level' => '',
        'is_featured' => false,
        'status' => true,
        'preparation_steps' => [],
        'cooking_instructions' => [],
        'equipment_needed' => [],
        'chef_notes' => '',
        'video_url' => '',
    ];

    public $selectedIngredients = [];
    public $ingredientSearch = '';
    public $showIngredientModal = false;
    public $dateRange = [
        'start' => null,
        'end' => null
    ];
    public $promotionalCombos = [];
    public $seasonalBundles = [];
    public $happyHourDeals = [];
    public $reportingMetrics = [];

    protected $listeners = [
        'refreshRecipes' => '$refresh',
        'ingredientSelected' => 'addIngredient',
        'confirmDelete' => 'deleteRecipe',
        'dateRangeSelected' => 'updateDateRange'
    ];

    protected $rules = [
        'recipeData.name' => 'required|string|max:255',
        'recipeData.description' => 'required|string',
        'recipeData.type' => 'required|string',
        'recipeData.preparation_time' => 'required|integer|min:1',
        'recipeData.cooking_time' => 'nullable|integer|min:0',
        'recipeData.serving_size' => 'nullable|integer|min:1',
        'recipeData.difficulty_level' => 'required|string',
        'selectedIngredients' => 'required|array|min:1',
        'recipeData.preparation_steps' => 'required|array|min:1',
    ];

    public function mount(): void
    {
        $this->recipe = new Recipe();
        $this->dateRange = [
            'start' => now()->subMonth()->format('Y-m-d'),
            'end' => now()->format('Y-m-d')
        ];
        $this->loadPromotionalData();
    }

    public function render()
    {
        $recipes = Recipe::query()
            ->with(['ingredients', 'product'])
            ->when($this->searchTerm, function ($query): void {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->filters['type'], function ($query): void {
                $query->where('type', $this->filters['type']);
            })
            ->when('' !== $this->filters['status'], function ($query): void {
                $query->where('status', $this->filters['status']);
            })
            ->when($this->filters['cost_min'], function ($query): void {
                $query->where('estimated_cost', '>=', $this->filters['cost_min']);
            })
            ->when($this->filters['cost_max'], function ($query): void {
                $query->where('estimated_cost', '<=', $this->filters['cost_max']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $availableIngredients = $this->getFilteredIngredients();
        $reportingMetrics = $this->getReportingMetrics();

        return view('livewire.admin.inventory.recipes.index', [
            'recipes' => $recipes,
            'recipeTypes' => RecipeType::cases(),
            'unitTypes' => UnitType::cases(),
            'availableIngredients' => $availableIngredients,
            'difficultyLevels' => ['easy', 'medium', 'hard', 'expert'],
            'promotionalCombos' => $this->promotionalCombos,
            'seasonalBundles' => $this->seasonalBundles,
            'happyHourDeals' => $this->happyHourDeals,
            'reportingMetrics' => $reportingMetrics,
        ]);
    }

    public function createRecipe(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editMode = false;
        $this->step = 1;
    }

    public function editRecipe(Recipe $recipe): void
    {
        $this->loadRecipeData($recipe);
        $this->showForm = true;
        $this->editMode = true;
        $this->step = 1;
    }

    public function nextStep(): void
    {
        $this->validateStep();
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function saveRecipe(): void
    {
        $this->validate();

        try {
            DB::transaction(function (): void {
                $recipe = $this->editMode
                    ? Recipe::findOrFail($this->recipe->id)
                    : new Recipe();

                // Ensure all required fields are set
                $recipe->name = $this->recipeData['name'];
                $recipe->description = $this->recipeData['description'];
                $recipe->type = $this->recipeData['type'];
                $recipe->preparation_time = $this->recipeData['preparation_time'];
                $recipe->cooking_time = $this->recipeData['cooking_time'];
                $recipe->serving_size = $this->recipeData['serving_size'];
                $recipe->difficulty_level = $this->recipeData['difficulty_level'];
                $recipe->is_featured = $this->recipeData['is_featured'];
                $recipe->status = $this->recipeData['status'];
                $recipe->preparation_steps = $this->recipeData['preparation_steps'];
                $recipe->cooking_instructions = $this->recipeData['cooking_instructions'] ?? [];
                $recipe->equipment_needed = $this->recipeData['equipment_needed'] ?? [];
                $recipe->chef_notes = $this->recipeData['chef_notes'];
                $recipe->video_url = $this->recipeData['video_url'];
                $recipe->estimated_cost = 0; // Will be calculated after ingredients are added
                $recipe->last_cost_update = now();
                $recipe->slug = Str::slug($this->recipeData['name']); // Generate slug from name

                $recipe->save();

                // Sync ingredients
                $ingredientData = collect($this->selectedIngredients)->mapWithKeys(fn ($ingredient) => [$ingredient['id'] => [
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'preparation_notes' => $ingredient['notes'] ?? null,
                ]]);
                $recipe->ingredients()->sync($ingredientData);

                // Calculate estimated cost based on ingredients
                $totalCost = $recipe->ingredients->sum(function ($ingredient) {
                    $pivotData = $ingredient->pivot;
                    return $ingredient->cost_per_unit * $pivotData->quantity;
                });
                $recipe->update(['estimated_cost' => $totalCost]);

                // Create or update associated product
                $recipe->product()->updateOrCreate(
                    [],
                    [
                        'name' => $recipe->name,
                        'description' => $recipe->description,
                        'status' => true,
                        'category_id' => $this->getCategoryIdForRecipeType($recipe->type),
                    ]
                );

                session()->flash('success', $this->editMode ? 'Recipe updated successfully!' : 'Recipe created successfully!');

                $this->resetForm();
            });
        } catch (Exception $e) {
            session()->flash('error', 'Error saving recipe: ' . $e->getMessage());
        }
    }

    public function addIngredient(Ingredient $ingredient): void
    {
        if ( ! isset($this->selectedIngredients[$ingredient->id])) {
            $this->selectedIngredients[$ingredient->id] = [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'quantity' => 1,
                'unit' => $ingredient->unit,
                'notes' => '',
            ];
        }
    }

    public function removeIngredient($ingredientId): void
    {
        unset($this->selectedIngredients[$ingredientId]);
    }

    public function updateIngredientQuantity($ingredientId, $quantity): void
    {
        if (isset($this->selectedIngredients[$ingredientId])) {
            $this->selectedIngredients[$ingredientId]['quantity'] = $quantity;
        }
    }

    public function addPreparationStep(): void
    {
        $this->recipeData['preparation_steps'][] = '';
    }

    public function removePreparationStep($index): void
    {
        unset($this->recipeData['preparation_steps'][$index]);
        $this->recipeData['preparation_steps'] = array_values($this->recipeData['preparation_steps']);
    }

    public function showOptimization(Recipe $recipe): void
    {
        $this->recipe = $recipe;
        $this->showOptimizationModal = true;
    }

    public function showSeasonalAlternatives(Recipe $recipe): void
    {
        $this->recipe = $recipe;
        $this->showSeasonalAlternativesModal = true;
    }

    public function replaceIngredient(Ingredient $original, Ingredient $alternative): void
    {
        $originalQuantity = $original->pivot->quantity;
        $originalUnit = $original->pivot->unit;

        $this->recipe->ingredients()->detach($original->id);
        $this->recipe->updateIngredient($alternative, $originalQuantity, $originalUnit);

        $this->recipe->refresh();
        $this->showSeasonalAlternativesModal = false;

        session()->flash('success', 'Ingredient replaced successfully!');
    }

    public function toggleRecipeStatus(Recipe $recipe): void
    {
        $recipe->update(['status' => ! $recipe->status]);
        session()->flash('success', 'Recipe status updated successfully!');
    }

    public function duplicateRecipe(Recipe $recipe): void
    {
        $clone = $recipe->duplicate();
        session()->flash('success', 'Recipe duplicated successfully!');
    }

    public function calculateProfitMargin(Recipe $recipe, $sellingPrice)
    {
        return $recipe->calculateProfitMargin((float) $sellingPrice);
    }

    public function resetFilters(): void
    {
        $this->reset('filters', 'searchTerm');
    }

    public function updateDateRange($start, $end): void
    {
        $this->dateRange = [
            'start' => $start,
            'end' => $end
        ];
    }

    public function showPromotions(): void
    {
        $this->loadPromotionalData();
        $this->showPromotionsModal = true;
    }

    public function showReporting(): void
    {
        $this->showReportingModal = true;
    }

    protected function loadPromotionalData(): void
    {
        $this->promotionalCombos = $this->getPromotionalCombos();
        // $this->seasonalBundles = $this->getSeasonalBundles();
        $this->happyHourDeals = $this->getHappyHourDeals();
    }

    protected function getPromotionalCombos(): Collection
    {
        return
            $juices = Product::whereHas('category', function ($query): void {
                $query->where('name', 'Juices');
            })->available()->get();

        $driedFruits = Product::whereHas('category', function ($query): void {
            $query->where('name', 'Dried Fruits');
        })->available()->get();

        $combos = collect();

        // Generate juice pairs
        $juices->each(function ($juice1) use ($juices, &$combos): void {
            $juices->each(function ($juice2) use ($juice1, &$combos): void {
                if ($juice1->id < $juice2->id) {
                    $totalPrice = $this->calculateComboPrice([$juice1, $juice2]);
                    $combos->push([
                        'name' => "Duo {$juice1->name} & {$juice2->name}",
                        'products' => [$juice1, $juice2],
                        'original_price' => $juice1->price + $juice2->price,
                        'combo_price' => $totalPrice,
                        'savings' => ($juice1->price + $juice2->price) - $totalPrice,
                        'type' => 'juice_pair'
                    ]);
                }
            });
        });

        return $combos->sortByDesc('savings')->take(10);
    }

    protected function getHappyHourDeals(): array
    {
        $currentHour = now()->hour;

        if ($currentHour >= 15 && $currentHour < 17) {
            return [
                'active' => true,
                'discount_percentage' => 30,
                'remaining_time' => now()->setHour(17)->setMinute(0)->setSecond(0)->diffForHumans(now()),
                'products' => Product::whereHas('category', function ($query): void {
                    $query->where('name', 'Juices');
                })->available()->get()->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'original_price' => $product->price,
                    'happy_hour_price' => $product->price * 0.7,
                ])
            ];
        }

        return [
            'active' => false,
            'next_happy_hour' => now()->hour >= 17
                ? now()->addDay()->setHour(15)->setMinute(0)->setSecond(0)
                : now()->setHour(15)->setMinute(0)->setSecond(0),
        ];
    }

    protected function getReportingMetrics(): array
    {
        $startDate = Carbon::parse($this->dateRange['start']);
        $endDate = Carbon::parse($this->dateRange['end']);

        return [
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'low_stock' => $this->getLowStockProducts(),
            'category_performance' => $this->getCategoryPerformance($startDate, $endDate),
            'recipe_metrics' => $this->getRecipeMetrics($startDate, $endDate),
        ];
    }

    protected function getTopProducts(Carbon $startDate, Carbon $endDate): Collection
    {
        return Product::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
    }

    // getCategoryPerformance
    protected function getCategoryPerformance(Carbon $startDate, Carbon $endDate): Collection
    {
        return Category::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
    }

    // getRecipeMetrics
    protected function getRecipeMetrics(Carbon $startDate, Carbon $endDate): Collection
    {
        return Recipe::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
    }

    protected function getLowStockProducts(int $limit = 5): Collection
    {
        return Product::where('stock_quantity', '<=', DB::raw('reorder_point'))
            ->where('status', true)
            ->orderBy('stock_quantity')
            ->take($limit)
            ->get();
    }

    protected function validateStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'recipeData.name' => 'required|string|max:255',
                'recipeData.description' => 'required|string',
                'recipeData.type' => 'required|string',
            ]),
            2 => $this->validate(['selectedIngredients' => 'required|array|min:1']),
            3 => $this->validate([
                'recipeData.preparation_steps' => 'required|array|min:1',
                'recipeData.preparation_time' => 'required|integer|min:1',
            ]),
            default => null,
        };
    }

    protected function getFilteredIngredients(): Collection
    {
        return Ingredient::query()
            ->when($this->ingredientSearch, function ($query): void {
                $query->where('name', 'like', "%{$this->ingredientSearch}%");
            })
            ->where('status', true)
            ->get();
    }

    protected function getCategoryIdForRecipeType($type): int
    {
        // Implement your category mapping logic here
        return 1; // Default category ID
    }

    protected function loadRecipeData(Recipe $recipe): void
    {
        $this->recipe = $recipe;
        $this->recipeData = $recipe->only([
            'name',
            'description',
            'type',
            'preparation_time',
            'cooking_time',
            'serving_size',
            'difficulty_level',
            'is_featured',
            'status',
            'preparation_steps',
            'cooking_instructions',
            'equipment_needed',
            'chef_notes',
            'video_url'
        ]);

        $this->selectedIngredients = $recipe->ingredients->mapWithKeys(fn ($ingredient) => [$ingredient->id => [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'quantity' => $ingredient->pivot->quantity,
            'unit' => $ingredient->pivot->unit,
            'notes' => $ingredient->pivot->preparation_notes,
        ]])->toArray();
    }

    protected function resetForm(): void
    {
        $this->recipe = new Recipe();
        $this->recipeData = [
            'name' => '',
            'description' => '',
            'type' => '',
            'preparation_time' => null,
            'cooking_time' => null,
            'serving_size' => null,
            'difficulty_level' => '',
            'is_featured' => false,
            'status' => true,
            'preparation_steps' => [],
            'cooking_instructions' => [],
            'equipment_needed' => [],
            'chef_notes' => '',
            'video_url' => '',
        ];
        $this->selectedIngredients = [];
        $this->showForm = false;
        $this->editMode = false;
        $this->step = 1;
    }

    protected function calculateComboPrice(array $products): float
    {
        $totalOriginalPrice = collect($products)->sum('price');
        $discounts = [
            'juice_pair' => 0.15,
            'juice_dried_fruit' => 0.20,
            'family_pack' => 0.25,
        ];

        $comboType = match (count($products)) {
            2 => $this->areAllJuices($products) ? 'juice_pair' : 'juice_dried_fruit',
            3 => 'family_pack',
            default => null
        };

        return $comboType ? $totalOriginalPrice * (1 - $discounts[$comboType]) : $totalOriginalPrice;
    }

    protected function areAllJuices(array $products): bool
    {
        return collect($products)->every(fn ($product) => 'Juices' === $product->category->name);
    }
}
