<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Fresh Produce Co.',
                'contact_person' => 'John Smith',
                'email' => 'john@freshproduce.com',
                'phone' => '+1-555-0123',
                'address' => '123 Farmer\'s Market Lane',
                'payment_terms' => [
                    'method' => 'bank_transfer',
                    'days' => 30,
                    'minimum_order' => 500,
                ],
                'delivery_terms' => [
                    'free_shipping_minimum' => 1000,
                    'delivery_days' => 2,
                    'shipping_cost' => 25.00,
                ],
            ],
            // Add more predefined suppliers...
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create additional random suppliers if in development
        if (app()->environment('local', 'staging')) {
            Supplier::factory()->count(5)->create();
        }
    }
} 