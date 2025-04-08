<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Http\Controllers\CheckoutController;
use App\Livewire\Admin\Dashboard\Index as AdminDashboardIndex;
use App\Livewire\Admin\Products\Index as AdminProductIndex;
use App\Livewire\Admin\Products\Composable\Index as AdminComposableProductIndex;
use App\Livewire\Admin\Categories\Index as AdminCategoryIndex;
use App\Livewire\Admin\Inventory\Index as AdminInventoryIndex;
use App\Livewire\Admin\Inventory\Ingredients\Index as AdminIngredientIndex;
use App\Livewire\Admin\Inventory\Recipes\Index as AdminRecipeIndex;
use App\Livewire\Admin\Inventory\Waste\Index as AdminWasteIndex;
use App\Livewire\Admin\Orders\Index as AdminOrderIndex;
use App\Livewire\Admin\Kitchen\Index as AdminKitchenIndex;
use App\Livewire\Admin\Kitchen\Display as AdminKitchenDisplay;
use App\Livewire\Admin\Kitchen\Dashboard as AdminKitchenDashboard;
use App\Livewire\Admin\Finance\CashRegister\Index as AdminCashRegisterIndex;
use App\Livewire\Admin\Finance\Expenses\Index as AdminExpenseIndex;
use App\Livewire\Admin\Finance\Expenses\Categories\Index as AdminExpenseCategoryIndex;
use App\Livewire\Admin\Finance\Purchases\Index as AdminPurchaseIndex;
use App\Livewire\Admin\Suppliers\Index as AdminSupplierIndex;
use App\Livewire\Admin\Settings\Index as AdminSettingsIndex;
use App\Livewire\Admin\Settings\Languages\Index as AdminLanguageIndex;
use App\Livewire\Admin\Analytics\Index as AdminAnalyticsIndex;
use App\Livewire\{
    ComposableProductIndex,
    Index,
    MenuIndex,
    Pos,
    TvMenu,
};
use App\Livewire\Installation\StepManager;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\DynamicPage;

// Public routes
Route::get('/', MenuIndex::class)->name('index');

Route::get('/menu/tv', TvMenu::class)->name('menu.tv');

// Composable product routes
Route::prefix('compose')->name('compose.')->group(function (): void {
    Route::get('/{productType}', ComposableProductIndex::class)->name('product');
});

// Composable Product Routes
Route::get('/composable/{productType}', ComposableProductIndex::class)
    ->name('composable.product');

Route::get('/pos', Pos::class)->name('pos');

// Admin routes
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function (): void {
    // Dashboard
    Route::get('/dashboard', AdminDashboardIndex::class)->name('dashboard');

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
    Route::get('/finance/expense-categories', AdminExpenseCategoryIndex::class)->name('finance.expense-categories');

    // Supplier Management Routes
    Route::get('/suppliers', AdminSupplierIndex::class)->name('suppliers');

    // Kitchen Management
    Route::get('/kitchen', AdminKitchenIndex::class)->name('kitchen');
    Route::get('/kitchen/display', AdminKitchenDisplay::class)->name('kitchen.display');
    Route::get('/kitchen/dashboard', AdminKitchenDashboard::class)->name('kitchen.dashboard');

    // Settings
    Route::get('/settings', AdminSettingsIndex::class)->name('settings');
    Route::get('/settings/languages', AdminLanguageIndex::class)->name('settings.languages');

    // Analytics
    // Route::get('/analytics', AdminAnalyticsIndex::class)->name('analytics');
});

// Profile routes
Route::middleware('auth')->group(function (): void {
    Route::view('profile', 'profile')->name('profile');
});

// if not found redirect to home page
// Route::fallback(fn () => redirect()->route('index'));

Route::middleware('guest')->group(function (): void {
    Route::get('/register', function (): void {})->name('register');

    Route::post('/register', [PhoneVerificationController::class, 'register'])
        ->name('register.store');
});


Volt::route('test-pdf', 'pages.test-pdf')
    ->name('test-pdf');


// Installation route - only accessible if not installed
Route::middleware(['installation.check'])->group(function () {
    Route::get('/install', StepManager::class)->name('installation');
});

// Dynamic page routes
// Route::get('/page/{slug}', DynamicPage::class)
//     ->where('slug', '^(?!admin|install|shop).*$')
//     ->name('page.show');

require __DIR__ . '/auth.php';
