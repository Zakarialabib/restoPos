<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\{Category, Ingredient, OrderItem, Product};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_KEYS = [
        'products' => 'menu:products',
        'categories' => 'menu:categories',
        'ingredients' => 'menu:ingredients',
        'featured' => 'menu:featured',
    ];

    public function getActiveProducts(): Collection
    {
        return Cache::remember(self::CACHE_KEYS['products'], self::CACHE_TTL, fn () => Product::query()
            ->active()
            ->with(['category'])
            ->orderBy('name')
            ->get());
    }

    public function getComposableCategories(): Collection
    {
        return Cache::remember(self::CACHE_KEYS['categories'], self::CACHE_TTL, fn () => Category::query()
            ->select('id', 'name', 'status', 'is_composable', 'slug')
            ->where('status', true)
            ->where('is_composable', true)
            ->orderBy('name')
            ->get());
    }

    public function getAvailableIngredients(): Collection
    {
        return Cache::remember(self::CACHE_KEYS['ingredients'], self::CACHE_TTL, fn () => Ingredient::query()
            ->select('id', 'name', 'type', 'is_seasonal', 'stock_quantity', 'unit')
            ->where('status', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('type')
            ->orderBy('name')
            ->get());
    }

    public function getFeaturedProducts(): Collection
    {
        return Cache::remember(self::CACHE_KEYS['featured'], self::CACHE_TTL, fn () => Product::query()
            ->active()
            ->where('is_featured', true)
            ->with(['category'])
            ->orderBy('name')
            ->get());
    }

    public function getProductsByCategory(string $categorySlug): Collection
    {
        $cacheKey = "menu:category:{$categorySlug}";

        return Cache::remember($cacheKey, self::CACHE_TTL, fn () => Product::query()
            ->active()
            ->whereHas('category', fn ($query) => $query->where('slug', $categorySlug))
            ->with(['category'])
            ->orderBy('name')
            ->get());
    }

    public function clearMenuCache(): void
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }

        // Clear category-specific caches
        $categories = Category::pluck('slug');
        foreach ($categories as $slug) {
            Cache::forget("menu:category:{$slug}");
        }
    }

    public function getPopularJuiceCombinations(): Collection
    {
        return OrderItem::query()
            ->where('is_composable', true)
            ->select('composition', DB::raw('COUNT(*) as order_count'))
            ->groupBy('composition')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $composition = $item->composition;
                return [
                    'id' => md5(json_encode($composition)),
                    'name' => $this->generateCombinationName($composition),
                    'composition' => $composition,
                    'popularity' => $item->order_count,
                    'preview' => $this->generatePreviewData($composition)
                ];
            });
    }

    protected function generateCombinationName(array $composition): string
    {
        $fruits = Ingredient::whereIn('id', $composition['fruits'])
            ->take(3)
            ->pluck('name')
            ->join(', ');

        return __('Popular Mix') . ": {$fruits}";
    }

    protected function generatePreviewData(array $composition): array
    {
        $fruits = Ingredient::whereIn('id', $composition['fruits'])->get();

        return [
            'fruits' => $fruits->map(fn ($fruit) => [
                'name' => $fruit->name,
                'image' => $fruit->image,
                'benefits' => $fruit->nutritional_info
            ])->toArray(),
            'base' => $composition['base'],
            'sugar' => $composition['sugar'],
            'size' => $composition['size'],
            'addons' => $composition['addons'] ?? []
        ];
    }
}
