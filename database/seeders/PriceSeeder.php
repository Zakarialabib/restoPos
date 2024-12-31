<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Add different sizes for juice products
            if ('Smoothies & Jus - عصائر و سموذي' === $product->category->name) {
                $product->prices()->createMany([
                    [
                        'cost' => 8.00,
                        'price' => 12.00,
                        'date' => now(),
                        'notes' => __('Small size'),
                        'metadata' => [
                            'size' => 'small',
                            'unit' => 'ml',
                            'volume' => 250,
                        ],
                    ],
                    [
                        'cost' => 12.00,
                        'price' => 18.00,
                        'date' => now(),
                        'notes' => __('Medium size'),
                        'metadata' => [
                            'size' => 'medium',
                            'unit' => 'ml',
                            'volume' => 350,
                        ],
                    ],
                    [
                        'cost' => 15.00,
                        'price' => 22.00,
                        'date' => now(),
                        'notes' => __('Large size'),
                        'metadata' => [
                            'size' => 'large',
                            'unit' => 'ml',
                            'volume' => 500,
                        ],
                    ],
                ]);
            } else {
                // Standard pricing for other products
                $product->prices()->create([
                    'cost' => $product->base_price * 0.6, // 40% margin
                    'price' => $product->base_price,
                    'date' => now(),
                    'metadata' => [
                        'size' => 'standard',
                        'unit' => 'piece',
                    ],
                ]);
            }
        }
    }
}
