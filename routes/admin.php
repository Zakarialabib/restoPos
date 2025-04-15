<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard\Index as Dashboard;
use App\Livewire\Admin\Orders\Index as Orders;
use App\Livewire\Admin\Page\Settings;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Products\Index as AdminProductIndex;
use App\Livewire\Admin\Categories\Index as AdminCategoryIndex;
use App\Livewire\Admin\Inventory\Index as AdminInventoryIndex;
use App\Livewire\Admin\Inventory\Ingredients\Index as AdminIngredientIndex;
use App\Livewire\Admin\Inventory\Recipes\Index as AdminRecipeIndex;
use App\Livewire\Admin\Inventory\Waste\Index as AdminWasteIndex;
use App\Livewire\Admin\Products\Composable\Index as AdminComposableProductIndex;
use App\Livewire\Admin\Orders\Index as AdminOrderIndex;
use App\Livewire\Admin\Finance\CashRegister\Index as AdminCashRegisterIndex;
use App\Livewire\Admin\Finance\Purchases\Index as AdminPurchaseIndex;
use App\Livewire\Admin\Finance\Expenses\Index as AdminExpenseIndex;
use App\Livewire\Admin\SupplierManagement as AdminSupplierIndex;
use App\Livewire\Admin\Kitchen\Index as AdminKitchenIndex;
use App\Livewire\Admin\Kitchen\Display as AdminKitchenDisplay;
use App\Livewire\Admin\Kitchen\Dashboard as AdminKitchenDashboard;
use App\Livewire\Admin\Language\Index as AdminLanguageIndex;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {
        // Dashboard
        Route::get('dashboard', Dashboard::class)->name('dashboard');


        // Admin routes
        Route::get('dashboard', Dashboard::class)->name('dashboard');

        // Product Management
        Route::get('/products', AdminProductIndex::class)->name('products');
        Route::get('/categories', AdminCategoryIndex::class)->name('categories');

        // Inventory Management (Ingredients, Recipes, Waste)
        Route::get('/inventory', AdminInventoryIndex::class)->name('inventory');
        Route::get('/inventory/ingredients', AdminIngredientIndex::class)->name('inventory.ingredients');
        Route::get('/inventory/recipes', AdminRecipeIndex::class)->name('inventory.recipes');
        Route::get('/inventory/waste', AdminWasteIndex::class)->name('inventory.waste');

        // Composable Products Management
        Route::get('/products/composable', AdminComposableProductIndex::class)->name('products.composable');

        // Operations Management
        Route::get('/orders', AdminOrderIndex::class)->name('orders');

        // Finance Management (Cash Register, Purchases, Expenses, Expense Categories)
        Route::get('/finance/cash-register', AdminCashRegisterIndex::class)->name('finance.cash-register');
        Route::get('/finance/purchases', AdminPurchaseIndex::class)->name('finance.purchases');
        Route::get('/finance/expenses', AdminExpenseIndex::class)->name('finance.expenses');

        // Supplier Management Routes
        Route::get('/suppliers', AdminSupplierIndex::class)->name('suppliers');

        // Kitchen Management
        Route::get('/kitchen', AdminKitchenIndex::class)->name('kitchen');
        Route::get('/kitchen/display', AdminKitchenDisplay::class)->name('kitchen.display');
        Route::get('/kitchen/dashboard', AdminKitchenDashboard::class)->name('kitchen.dashboard');

        // Settings
        Route::get('/settings/languages', AdminLanguageIndex::class)->name('settings.languages');

        // Analytics
        // Route::get('/analytics', AdminAnalyticsIndex::class)->name('analytics');


        // Menu Management
        // Route::prefix('menu')->name('menu.')->group(function () {
        //     Route::get('/', \App\Livewire\Pages\Admin\Menu\Index::class)->name('index');
        //     Route::get('create', \App\Livewire\Pages\Admin\Menu\Create::class)->name('create');
        //     Route::get('{item}/edit', \App\Livewire\Pages\Admin\Menu\Edit::class)->name('edit');
        // });

        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', Orders::class)->name('index');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', Settings::class)->name('index');
        });
    });
