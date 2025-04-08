<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.guest')]
#[Title('Menu')]
class MenuIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public ?string $category = null;

    #[Url(except: '')]
    public ?string $search = null;

    #[Url(except: 'name')]
    public string $sort = 'name';

    public bool $hasMore = false;
    public int $perPage = 12;

    protected $queryString = [
        'category' => ['except' => ''],
        'search' => ['except' => ''],
        'sort' => ['except' => 'name'],
    ];

    public function render(ProductService $productService)
    {
        $categories = Category::with([
            'products' => fn ($query) => $query->active()->with('category'),
            'composableConfiguration' => fn ($query) => $query->where('is_active', true)
        ])->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'is_composable' => $category->isComposable(),
            'composable_features' => $category->getComposableFeatures(),
            'products' => $category->products->map(fn ($product) => $this->transformProduct($product))
        ]);

        $products = $productService->getFilteredProducts(
            $this->category,
            $this->search,
            $this->sort,
            $this->perPage
        );

        $transformedProducts = $products->through(fn ($product) => $this->transformProduct($product));

        // Check if there are more products to load
        $this->hasMore = $products->hasMorePages();

        return view('livewire.menu-index', [
            'categories' => $categories,
            'products' => [
                'data' => $transformedProducts->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $this->hasMore,
            ],
            'filters' => [
                'search' => $this->search,
                'category' => $this->category,
                'sort' => $this->sort,
            ],
        ]);
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function loadMoreProducts(): void
    {
        $this->perPage += 12;
    }

    public function resetFilters(): void
    {
        $this->reset(['category', 'search', 'sort']);
        $this->resetPage();
    }

    protected function transformProduct($product): array
    {
        // Get the base price (lowest price from sizes or default price)
        $basePrice = $product->prices->count() > 0
            ? $product->prices->min('price')
            : $product->price ?? 0;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $basePrice,
            'prices' => $product->prices->map(fn ($price) => [
                'size' => $price->metadata['size'] ?? 'default',
                'price' => $price->price * $this->getSeasonalPriceMultiplier($product),
            ])->values()->toArray(),
            'image' => $product->image,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
                'is_composable' => $product->category->isComposable(),
                'composable_features' => $product->category->getComposableFeatures(),
            ] : null,
            'stock_quantity' => (float) $product->stock_quantity,
            'isAvailableForOrder' => $product->status && ($product->stock_quantity > 0 || $product->prices->count() > 0),
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'is_composable' => (bool) $product->is_composable,
            'category_slug' => $product->category?->slug,
        ];
    }

    private function getSeasonalPriceMultiplier($product): float
    {
        return 1.0;
    }
}
