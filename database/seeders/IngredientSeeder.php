<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            [
                'name' => 'Apple',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 1000,
                'batch_number' => 'BATCH001',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 200,
            ],
            [
                'name' => 'Banana',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 1500,
                'batch_number' => 'BATCH002',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 300,
            ],
            [
                'name' => 'Orange',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 800,
                'batch_number' => 'BATCH003',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 150,
            ],
            [
                'name' => 'Pineapple',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 500,
                'batch_number' => 'BATCH004',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 100,
            ],
            [
                'name' => 'Peach',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 750,
                'batch_number' => 'BATCH005',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 150,
            ],
            [
                'name' => 'Avocado',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 600,
                'batch_number' => 'BATCH006',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 120,
            ],
            [
                'name' => 'Pear',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 900,
                'batch_number' => 'BATCH007',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 180,
            ],
            [
                'name' => 'Dragon Fruit',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 300,
                'batch_number' => 'BATCH008',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 60,
            ],
            [
                'name' => 'Papaya',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 400,
                'batch_number' => 'BATCH009',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 80,
            ],
            [
                'name' => 'Milk',
                'type' => 'liquid',
                'unit' => 'ml',
                'conversion_rate' => 1,
                'stock' => 1000,
                'batch_number' => 'BATCH010',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 200,
            ],
            [
                'name' => 'Ice',
                'type' => 'ice',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 1000,
                'batch_number' => 'BATCH011',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 200,
            ],
            [
                'name' => 'Lemon',
                'type' => 'fruit',
                'unit' => 'g',
                'conversion_rate' => 1,
                'stock' => 500,
                'batch_number' => 'BATCH012',
                'expiry_date' => '2024-12-31',
                'reorder_level' => 100,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}