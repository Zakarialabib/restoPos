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
                'stockable_type' => Ingredient::class,
                'stockable_id' => $ingredient->id,
                'quantity' => rand(10, 100),
                'minimum_quantity' => 5,
                'maximum_quantity' => 200,
                'notes' => "Initial stock for {$ingredient->name}",
                'metadata' => [
                    'unit' => $ingredient->unit ?? 'kg',
                    'location' => 'Main Storage',
                    'reorder_point' => 10,
                ],
                'last_restocked_at' => now(),
                'last_used_at' => now(),
            ]);
        }
    }
}
