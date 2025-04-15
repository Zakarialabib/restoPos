<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, create roles and permissions
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Then create users and admins
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);

        // Run other seeders if in local or staging environment
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
            ]);
        }
    }
}
