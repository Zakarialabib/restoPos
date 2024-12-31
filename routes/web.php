<?php

declare(strict_types=1);

use App\Livewire\Admin\{
    CategoryManagement,
    Dashboard,
    IngredientManagement,
    InventoryManagement,
    OrderManagement,
    ProductManagement,
    RecipeManagement,
};
use App\Livewire\{
    ComposableDriedFruitsIndex,
    ComposableJuicesIndex,
    ComposableSaladeIndex,
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Livewire\Volt\Volt;

// Public routes
Route::get('/', [MenuController::class, 'index'])->name('index');
Route::get('/menu/tv', [MenuController::class, 'tvMenu'])->name('menu.tv');

// Composable product routes
Route::prefix('compose')->name('compose.')->group(function (): void {
    Route::get('/juices', ComposableJuicesIndex::class)->name('juices');
    Route::get('/salade', ComposableSaladeIndex::class)->name('salade');
    Route::get('/dried-fruits', ComposableDriedFruitsIndex::class)->name('dried-fruits');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function (): void {
    // Dashboards
    // Route::get('/dashboard', UnifiedDashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Product Management
    Route::get('/products', ProductManagement::class)->name('products');
    Route::get('/categories', CategoryManagement::class)->name('categories');
    Route::get('/ingredients', IngredientManagement::class)->name('ingredients');
    Route::get('/recipes', RecipeManagement::class)->name('recipes');

    // Operations Management
    Route::get('/orders', OrderManagement::class)->name('orders');
    Route::get('/inventory', InventoryManagement::class)->name('inventory');
});

// Profile routes
Route::middleware('auth')->group(function (): void {
    Route::view('profile', 'profile')->name('profile');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/order', [CheckoutController::class, 'submit'])->name('order.submit');
Route::get('/orders/{order}', [OrderController::class, 'track'])->name('order.track');

// if not found redirect to home page
// Route::fallback(fn () => redirect()->route('index'));

// checking api , ProductResource , CategoryResource , PriceResource
Route::get('/api/products', function () {
    return ProductResource::collection(Product::query()->with('category')->get());
});

require __DIR__ . '/auth.php';
