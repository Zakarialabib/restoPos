<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = Ingredient::all();

        foreach ($ingredients as $ingredient) {
            Stock::create([
                'ingredient_id' => $ingredient->id,
                'quantity' => rand(10, 100),
                'unit' => $ingredient->unit ?? 'kg',
                'minimum_quantity' => 5,
                'reorder_point' => 10,
                'location' => 'Main Storage',
                'last_restocked_at' => now(),
                'last_checked_at' => now(),
            ]);
        }
    }
}
