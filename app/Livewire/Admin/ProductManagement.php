<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Livewire\Utils\Datatable;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\PriceManagementService;
use App\Services\ProductAnalyticsService;
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

#[Layout('layouts.admin')]
#[Title('Product Management')]
class ProductManagement extends Component
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

    public $startDate = null;
    public $endDate = null;

    #[Validate('array')]
    public array $selectedProducts = [];

    public $showBulkActions = false;

    // UI States
    public $showForm = false;
    public $showIngredientModal = false;
    public $showPriceHistory = false;
    public $showStockHistory = false;
    public $editingProductId = null;

    // Form Properties
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate('required|exists:categories,id')]
    public $category_id = '';

    #[Validate('required|numeric|min:0')]
    public $price = 0;

    #[Validate('required|numeric|min:0')]
    public $cost = 0;

    #[Validate('required|numeric|min:0')]
    public $stock = 0;

    #[Validate('required|numeric|min:0')]
    public $reorder_point = 0;

    #[Validate('required|boolean')]
    public $status = true;

    #[Validate('required|boolean')]
    public $is_featured = false;

    #[Validate('required|boolean')]
    public $is_composable = false;

    #[Validate('nullable|image|max:2048|mimes:jpg,jpeg,png,webp')]
    public $image;

    // Size-based Pricing
    #[Validate('array')]
    public $sizePrices = [];

    public $selectedSize;
    public $selectedUnit;

    // Ingredient Management
    #[Validate('array')]
    public $selectedIngredients = [];

    public $searchIngredient = '';
    public $allowedIngredients = [];
    public $basePrice = 0.00;

    // Stock Adjustment
    #[Validate('numeric|min:0')]
    public $adjustmentQuantity = 0;

    #[Validate('string|max:255')]
    public $adjustmentReason = '';

    // Services
    protected ProductService $productService;
    protected ProductStockService $stockService;
    protected PriceManagementService $priceService;
    protected ProductAnalyticsService $analyticsService;

    public $model = Product::class;

    public function boot(
        ProductService $productService,
        ProductStockService $stockService,
        PriceManagementService $priceService,
        ProductAnalyticsService $analyticsService
    ): void {
        $this->productService = $productService;
        $this->stockService = $stockService;
        $this->priceService = $priceService;
        $this->analyticsService = $analyticsService;
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->with(['category', 'prices', 'ingredients'])
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
                $this->status_filter === 'available',
                fn($query) =>
                $query->available()
            )
            ->when(
                $this->status_filter === 'unavailable',
                fn($query) =>
                $query->where('status', false)
            )
            ->when(
                $this->status_filter === 'low_stock',
                fn($query) =>
                $query->lowStock()
            )
            ->when(
                $this->status_filter === 'featured',
                fn($query) =>
                $query->featured()
            )
            ->when($this->dateRange, function ($query) {
                [$start, $end] = explode(' to ', $this->dateRange);
                $this->startDate = Carbon::parse($start)->startOfDay();
                $this->endDate = Carbon::parse($end)->endOfDay();
                return $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function ingredients()
    {
        return Ingredient::query()
            ->when(
                $this->searchIngredient,
                fn($query) =>
                $query->where('name', 'like', '%' . $this->searchIngredient . '%')
            )
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function productAnalytics()
    {
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfMonth();

        return $this->productService->getProductAnalytics($startDate, $endDate);
    }

    #[Computed]
    public function stockAlerts()
    {
        return $this->stockService->getStockAlerts($this->category_filter);
    }

    public function saveProduct(): void
    {
        $this->validate();

        try {
            // Handle image upload
            if ($this->image && !is_string($this->image)) {
                $fileName = Str::slug($this->name) . '-' . time() . '.' . $this->image->getClientOriginalExtension();
                $imagePath = $this->image->storeAs('products', $fileName, 'public');
            }

            $productData = [
                'name' => $this->name,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'status' => $this->status,
                'is_featured' => $this->is_featured,
                'is_composable' => $this->is_composable,
                'stock' => $this->stock,
                'reorder_point' => $this->reorder_point,
                'cost' => $this->cost,
                'price' => $this->price,
                'image' => $imagePath ?? null,
            ];

            if ($this->editingProductId) {
                $product = Product::findOrFail($this->editingProductId);
                $this->productService->updateProduct(
                    $product,
                    $productData,
                    $this->sizePrices,
                    $this->selectedIngredients
                );
                $message = __('Product updated successfully.');
            } else {
                $this->productService->createProduct(
                    $productData,
                    $this->sizePrices,
                    $this->selectedIngredients
                );
                $message = __('Product created successfully.');
            }

            $this->reset();

            session()->flash('success', $message);
        } catch (Exception $e) {
            session()->flash('error', __('Error saving product: ') . $e->getMessage());
        }
    }

    public function editProduct(Product $product): void
    {
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->status = $product->status;
        $this->is_featured = $product->is_featured;
        $this->is_composable = $product->is_composable;
        $this->stock = $product->stock_quantity;
        $this->reorder_point = $product->reorder_point;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->image = $product->image;
        $this->sizePrices = $product->getSizePrices();
        $this->selectedIngredients = $product->getRequiredIngredients();
        $this->showForm = true;
    }

    public function deleteProduct(Product $product): void
    {
        try {
            $this->productService->deleteProduct($product);

            session()->flash('success', __('Product deleted successfully.'));
        } catch (Exception $e) {
            session()->flash('error', __('Error deleting product: ') . $e->getMessage());
        }
    }

    public function adjustStock(Product $product): void
    {
        $this->validate([
            'adjustmentQuantity' => 'required|numeric',
            'adjustmentReason' => 'required|string|max:255'
        ]);

        try {
            $this->stockService->adjustStock(
                $product,
                $this->adjustmentQuantity,
                $this->adjustmentReason
            );

            session()->flash('success', __('Stock adjusted successfully.'));
        } catch (Exception $e) {
            session()->flash('error',  __('Error adjusting stock: ') . $e->getMessage());
        }
    }

    public function toggleStatus(Product $product): void
    {
        try {
            $this->productService->toggleStatus($product);
            session()->flash('success', __('Product status updated successfully.'));
        } catch (Exception $e) {
            session()->flash('error',  __('Error adjusting stock: ') . $e->getMessage());
        }
    }

    public function toggleFeatured(Product $product): void
    {
        try {
            $this->productService->toggleFeatured($product);
            session()->flash('success', __('Product featured status updated successfully.'));
        } catch (Exception $e) {
            session()->flash('error',  __('Error updating product featured status: ') . $e->getMessage());
        }
    }

    public function addSizePrice(): void
    {
        $this->sizePrices[] = [
            'size' => '',
            'unit' => 'g',
            'cost' => 0,
            'price' => 0
        ];
    }

    public function removeSizePrice(int $index): void
    {
        unset($this->sizePrices[$index]);
        $this->sizePrices = array_values($this->sizePrices);
    }

    public function addIngredient(): void
    {
        $this->selectedIngredients[] = [
            'id' => '',
            'quantity' => 0,
            'unit' => 'g'
        ];
    }

    public function removeIngredient(int $index): void
    {
        unset($this->selectedIngredients[$index]);
        $this->selectedIngredients = array_values($this->selectedIngredients);
    }

    public function render()
    {
        return view('livewire.admin.product-management');
    }
}
