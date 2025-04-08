<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class MenuBuilderService
{
    public function buildDailyMenu(): array
    {
        return [
            'featured_items' => $this->getFeaturedItems(),
            'categories' => $this->getCategorizedMenu(),
            'promotions' => $this->getCurrentPromotions(),
        ];
    }

    protected function getFeaturedItems(): Collection
    {
        return Product::featured()
            ->available()
            ->with(['category', 'prices'])
            ->take(5)
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category->name,
                'prices' => $product->prices->map(fn ($price) => [
                    'size' => $price->metadata['size'] ?? 'default',
                    'price' => $price->price,
                ]),
                'image' => $product->image,
            ]);
    }


    protected function getCategorizedMenu(): Collection
    {
        return Category::with(['products' => function ($query): void {
            $query->available()
                ->with(['prices', 'ingredients']);
        }])
            ->get()
            ->map(fn ($category) => [
                'category' => $category->name,
                'products' => $category->products->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'prices' => $product->prices->map(fn ($price) => [
                        'size' => $price->metadata['size'] ?? 'default',
                        'price' => $price->price * $this->getSeasonalPriceMultiplier($product),
                    ]),
                    'image' => $product->image,
                ]),
            ]);
    }

    protected function getCurrentPromotions(): array
    {
        return [
            'daily_specials' => $this->getDailySpecials(),
            'combo_deals' => $this->getComboDeals(),
            'happy_hour' => $this->getHappyHourDeals(),
        ];
    }

    protected function getDailySpecials(): array
    {
        // Implementation for daily specials
        return [
            'title' => 'Daily Specials',
            'items' => Product::available()
                ->inRandomOrder()
                ->take(2)
                ->get()
                ->map(fn ($product) => [
                    'name' => $product->name,
                    'discount' => 15, // 15% off
                    'original_price' => $product->prices->first()->price ?? 0,
                    'discounted_price' => $product->prices->first()->price * 0.85 ?? 0,
                ]),
        ];
    }

    protected function getComboDeals(): array
    {
        return [
            'title' => 'Combo Deals',
            'description' => 'Save more with our specially curated combinations',
            'deals' => [
                [
                    'name' => 'Juice & Dried Fruit Combo',
                    'discount' => 20,
                    'products' => ['Any juice', 'Any dried fruit pack'],
                ],
                [
                    'name' => 'Family Pack',
                    'discount' => 25,
                    'products' => ['3 large juices', '2 fruit bowls'],
                ],
            ],
        ];
    }

    protected function getHappyHourDeals(): array
    {
        return [
            'title' => 'Happy Hour',
            'timing' => '3 PM - 5 PM',
            'discount' => 30,
            'description' => '30% off on all fresh juices',
        ];
    }

    protected function checkProductAvailability(Product $product): array
    {
        $status = $product->isAvailableForOrder();
        $message = $status ? 'Available' : 'Temporarily Unavailable';

        if ($status && $product->ingredients->contains('is_seasonal', true)) {
            $message = 'Seasonal - Limited Time';
        }

        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    protected function getSeasonalPriceMultiplier(Product $product): float
    {
        $seasonalIngredients = $product->ingredients->where('is_seasonal', true);

        if ($seasonalIngredients->isEmpty()) {
            return 1.0;
        }

        // Average the seasonal adjustments of all seasonal ingredients
        return $seasonalIngredients->avg(fn ($ingredient) => $ingredient->getSeasonalPriceAdjustment());
    }
}
