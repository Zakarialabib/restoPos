<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Livewire\Utils\Datatable;
use App\Models\Category;
use App\Models\Ingredient;
use App\Services\CostManagementService;
use App\Services\IngredientService;
use App\Services\IngredientStockService;
use App\Services\IngredientAnalyticsService;
use App\Services\IngredientCategoryService;
use App\Enums\CategoryType;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Enums\ItemType;

#[Layout('layouts.admin')]
#[Title('Ingredient Management')]
class IngredientManagement extends Component
{
    use WithFileUploads;
    use WithPagination;
    use Datatable;

    #[Validate('string|nullable')]
    public $category_filter = '';

    #[Validate('string|nullable')]
    public $status_filter = '';

    #[Validate('string|nullable')]
    public $dateRange = '';

    public $startDate;
    public $endDate;

    #[Validate('array')]
    public array $selectedIngredients = [];

    public $showBulkActions = false;

    // UI States
    public $showForm = false;
    public $showCostHistory = false;
    public $showStockHistory = false;
    public $editingIngredientId = null;

    // Form Properties
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate('required|exists:categories,id')]
    public $category_id = '';

    #[Validate('required|numeric|min:0')]
    public $cost = 0;

    #[Validate('required|numeric|min:0')]
    public $stock = 0;

    #[Validate('required|numeric|min:0')]
    public $reorder_point = 0;

    #[Validate('required|string')]
    public $unit = 'g';

    #[Validate('required|boolean')]
    public $status = true;

    #[Validate('nullable|date')]
    public $expiry_date = null;

    #[Validate('nullable|string')]
    public $storage_location = null;

    #[Validate('nullable|array')]
    public $supplier_info = [];

    #[Validate('nullable|image|max:2048|mimes:jpg,jpeg,png,webp')]
    public $image;

    public $selectAll = false;
    public $showPriceHistory = false;
    public $selectedIngredientId = null;
    public $priceHistory = [];

    // Stock Adjustment
    #[Validate('numeric|min:0')]
    public $adjustmentQuantity = 0;

    #[Validate('string|max:255')]
    public $adjustmentReason = '';

    // Cost Management
    public $newCost = [
        'cost' => 0,
        'notes' => ''
    ];

    // Analytics Filters
    public $analyticsStartDate = null;
    public $analyticsEndDate = null;
    public $selectedAnalyticsPeriod = '30';
    public $selectedAnalyticsType = 'cost';

    // Services
    protected IngredientService $ingredientService;
    protected IngredientStockService $stockService;
    protected IngredientAnalyticsService $analyticsService;
    protected CostManagementService $costService;
    protected IngredientCategoryService $ingredientCategoryService;

    public $model = Ingredient::class;

    public function boot(
        IngredientService $ingredientService,
        IngredientStockService $stockService,
        IngredientAnalyticsService $analyticsService,
        CostManagementService $costService
    ): void {
        $this->ingredientService = $ingredientService;
        $this->stockService = $stockService;
        $this->analyticsService = $analyticsService;
        $this->costService = $costService;
    }

    public $availableCategories;

    public function mount(): void
    {
        $this->ingredientCategoryService = app(IngredientCategoryService::class);
        $this->availableCategories = Category::whereIn('type', [
            CategoryType::INGREDIENT,
            CategoryType::BASE,
            CategoryType::FRUIT
        ])->get();
    }

    public function changeCategory(string $ingredientId, string $categoryId): void
    {
        $ingredient = Ingredient::findOrFail($ingredientId);
        $category = Category::findOrFail($categoryId);

        try {
            $this->ingredientCategoryService->reassignCategory($ingredient, $category);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => __('Category updated successfully')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Computed]
    public function ingredients()
    {
        $query = Ingredient::with(['category', 'prices', 'stockLogs'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->when(
                $this->category_filter,
                fn($query) =>
                $query->where('category_id', $this->category_filter)
            )
            ->when(
                $this->startDate && $this->endDate,
                fn($query) =>
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])
            )
            ->when($this->status_filter, function ($query) {
                return match ($this->status_filter) {
                    'low_stock' => $query->lowStock(),
                    'out_of_stock' => $query->outOfStock(),
                    'active' => $query->where('status', true),
                    'inactive' => $query->where('status', false),
                    default => $query
                };
            });

        return  $query->paginate($this->perPage);
    }

    #[Computed]
    public function categories()
    {
        return $this->ingredientCategoryService->getCategoriesByType(ItemType::INGREDIENT);
    }

    #[Computed]
    public function ingredientAnalytics()
    {
        return $this->analyticsService->getIngredientAnalytics(Carbon::parse($this->startDate), Carbon::parse($this->endDate));
    }

    #[Computed]
    public function wastageAnalytics()
    {
        return $this->analyticsService->getWastageAnalytics(Carbon::parse($this->startDate), Carbon::parse($this->endDate));
    }

    #[Computed]
    public function costAnalytics()
    {
        return $this->analyticsService->getCostAnalytics(Carbon::parse($this->startDate), Carbon::parse($this->endDate));
    }

    #[Computed]
    public function turnoverAnalytics()
    {
        return $this->analyticsService->getTurnoverAnalytics(Carbon::parse($this->startDate), Carbon::parse($this->endDate));
    }

    public function bulkUpdateStatus(bool $status): void
    {
        $this->ingredientService->bulkUpdateStatus($this->selectedIngredients, $status);
        $this->showBulkActions = false;
        session()->flash('message', 'Status updated successfully.');
    }

    #[Computed]
    public function seasonalityAnalytics()
    {
        return $this->analyticsService->getSeasonalityAnalytics();
    }

    public function saveIngredient(): void
    {
        $this->validate();

        try {
            // Handle image upload
            if ($this->image && !is_string($this->image)) {
                $fileName = Str::slug($this->name) . '-' . time() . '.' . $this->image->getClientOriginalExtension();
                $imagePath = $this->image->storeAs('ingredients', $fileName, 'public');
            }

            $ingredientData = [
                'name' => $this->name,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'status' => $this->status,
                'stock' => $this->stock,
                'reorder_point' => $this->reorder_point,
                'unit' => $this->unit,
                'cost' => $this->cost,
                'expiry_date' => $this->expiry_date,
                'storage_location' => $this->storage_location,
                'supplier_info' => $this->supplier_info,
                'image' => $imagePath ?? null,
            ];

            if ($this->editingIngredientId) {
                $ingredient = Ingredient::findOrFail($this->editingIngredientId);
                $this->ingredientService->updateIngredient($ingredient, $ingredientData);
                $message = __('Ingredient updated successfully.');
            } else {
                $this->ingredientService->createIngredient($ingredientData);
                $message = __('Ingredient created successfully.');
            }

            $this->reset();
            $this->dispatch('ingredient-saved');
            session()->flash('message', $message);
        } catch (Exception $e) {
            session()->flash('message', __('Error saving ingredient: ') . $e->getMessage());
        }
    }

    public function editIngredient(Ingredient $ingredient): void
    {
        $this->editingIngredientId = $ingredient->id;
        $this->name = $ingredient->name;
        $this->description = $ingredient->description;
        $this->category_id = $ingredient->category_id;
        $this->status = $ingredient->status;
        $this->stock = $ingredient->stock_quantity;
        $this->reorder_point = $ingredient->reorder_point;
        $this->unit = $ingredient->unit;
        $this->cost = $ingredient->cost;
        $this->expiry_date = $ingredient->expiry_date?->format('Y-m-d');
        $this->storage_location = $ingredient->storage_location;
        $this->supplier_info = $ingredient->supplier_info;
        $this->image = $ingredient->image;
        $this->showForm = true;
    }

    public function deleteIngredient(Ingredient $ingredient): void
    {
        try {
            $this->ingredientService->deleteIngredient($ingredient);
            $this->dispatch('ingredient-deleted');
            session()->flash('message', __('Ingredient deleted successfully.'));
        } catch (Exception $e) {
            session()->flash('message', __('Error deleting ingredient: ') . $e->getMessage());
        }
    }

    public function adjustStock(Ingredient $ingredient): void
    {
        $this->validate([
            'adjustmentQuantity' => 'required|numeric',
            'adjustmentReason' => 'required|string|max:255'
        ]);

        try {
            $this->stockService->adjustStock(
                $ingredient,
                $this->adjustmentQuantity,
                $this->adjustmentReason
            );
            $this->dispatch('stock-adjusted');
            session()->flash('message', __('Stock adjusted successfully.'));
        } catch (Exception $e) {
            session()->flash('message', __('Error adjusting stock: ') . $e->getMessage());
        }
    }

    public function toggleStatus(Ingredient $ingredient): void
    {
        try {
            $this->ingredientService->toggleStatus($ingredient);
            $this->dispatch('status-toggled');
            session()->flash('message', __('Ingredient status updated successfully.'));
        } catch (Exception $e) {
            session()->flash('message', __('Error updating ingredient status: ') . $e->getMessage());
        }
    }

    public function updateCost(int $ingredientId): void
    {
        $this->validate([
            'newCost.cost' => 'required|numeric|min:0',
            'newCost.notes' => 'nullable|string|max:255'
        ]);

        try {
            $ingredient = Ingredient::findOrFail($ingredientId);
            $this->costService->updateCost(
                $ingredient,
                $this->newCost['cost'],
                $this->newCost['notes']
            );
            $this->dispatch('cost-updated');
            session()->flash('message', __('Cost updated successfully.'));
        } catch (Exception $e) {
            session()->flash('message', __('Error updating cost: ') . $e->getMessage());
        }
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedIngredients = $this->ingredients->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIngredients = [];
        }
    }

    public function openStockHistory($ingredientId): void
    {
        $this->selectedIngredientId = $ingredientId;
        $this->showStockHistory = true;
    }

    public function priceHistoryModal($ingredientId): void
    {
        $this->selectedIngredientId = $ingredientId;
        $this->showPriceHistory = true;
    }

    public function render()
    {
        return view('livewire.admin.ingredient-management');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(ItemType::class)],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
