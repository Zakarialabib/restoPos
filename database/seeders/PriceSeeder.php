<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $customFruitBowl = Product::where('name', 'Custom Fruit Bowl')->first();

        if ($customFruitBowl) {
            $prices = [
                [
                    'priceable_type' => Product::class,
                    'priceable_id' => $customFruitBowl->id,
                    'cost' => 10.00,
                    'price' => 15.00,
                    'previous_cost' => 0,
                    'size' => 'regular',
                    'effective_date' => now(),
                    'entry_date' => now(),
                    'notes' => 'Regular size custom fruit bowl',
                    'is_current' => true,
                    'metadata' => [
                        'unit' => 'piece',
                    ],
                ]
            ];

            foreach ($prices as $price) {
                Price::create($price);
            }
        }
    }
}
