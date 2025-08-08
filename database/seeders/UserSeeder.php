<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo customer
        $demoUser = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
            ]
        );
        $demoUser->assignRole('customer');

        // Create additional demo customers
        $customer2 = User::updateOrCreate(
            ['email' => 'john.doe@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
            ]
        );
        $customer2->assignRole('customer');

        $customer3 = User::updateOrCreate(
            ['email' => 'jane.smith@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
            ]
        );
        $customer3->assignRole('customer');
    }
}
