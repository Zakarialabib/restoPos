<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Livewire\Admin\Dashboard\Index as Dashboard;
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
use App\Http\Controllers\TvMenuController;
use App\Livewire\TvMenu\Index as TvMenuIndex;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Admin\Dashboard as AdminDashboard;
use App\Livewire\Pages\Customer\Dashboard as CustomerDashboard;

// Public routes
Route::middleware('guest')->group(function () {
    Volt::route('login', 'pages.auth.login')->name('login');
});

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


// Customer routes
Route::prefix('customer')->name('customer.')->middleware('customer')->group(function () {
    Route::view('profile', 'profile')->name('profile');
    // Add other customer routes here
});

// Home route (redirects based on role)
// Route::get('/', function () {
//     if (auth()->check()) {
//         if (auth()->user()->hasRole(['admin', 'manager', 'staff'])) {
//             return redirect()->route('admin.dashboard');
//         }
//         return redirect()->route('customer.dashboard');
//     }
//     return redirect()->route('login');
// })->name('home');

// if not found redirect to home page
// Route::fallback(fn () => redirect()->route('index'));

Route::middleware('guest')->group(function (): void {

    Route::post('/register', [PhoneVerificationController::class, 'register'])
        ->name('register.store');
});


// Installation route - only accessible if not installed
// Route::middleware(['installation.check'])->group(function () {
Route::get('/install', StepManager::class)->name('installation');
// });

// Dynamic page routes
// Route::get('/page/{slug}', DynamicPage::class)
//     ->where('slug', '^(?!admin|install|shop).*$')
//     ->name('page.show');

Route::get('/tv-menu', TvMenuIndex::class)->name('tv-menu');

// Include all route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

// Fallback route
Route::fallback(fn () => redirect()->route('home'));
