<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // !! IMPORTANT: Remove or update this user creation logic !!
        // This hardcodes the 'role' column which we are replacing with Spatie
        /* Commenting out the old way
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                // 'role' => 'admin' // This line was the issue
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                // 'role' => 'user' // This line was the issue
            ]
        );
        */
        
        // Create or update users WITHOUT assigning the role directly here
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
        
        $demoUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
            ]
        );

        if (app()->environment('local', 'staging')) {
            $this->call([
                LanguagesSeeder::class,
                SettingSeeder::class,
                SectionSeeder::class,
                CategorySeeder::class,
                IngredientSeeder::class,
                ComposableConfigurationSeeder::class,
                PortionConfigurationSeeder::class,
                ProductSeeder::class,
                ComposableProductsSeeder::class,
                StockSeeder::class,
                RolesAndPermissionsSeeder::class,
            ]);
        }
        
        // Assign roles using Spatie AFTER users and roles are created
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        if ($demoUser) {
            // Decide what role the demo user should have, e.g., 'manager' or create a 'user' role
            // $demoUser->assignRole('manager'); 
            // Or create a 'user' role in RolesAndPermissionsSeeder and assign it:
            // $demoUser->assignRole('user'); 
        }
    }
}
