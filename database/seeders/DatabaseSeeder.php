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
            AdminSeeder::class,
            UserSeeder::class,
            // MenuSeeder::class,
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
            PriceSeeder::class,
        ]);
    }
}
