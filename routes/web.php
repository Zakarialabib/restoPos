<?php

declare(strict_types=1);

use App\Livewire\Admin\InventoryDashboard;
use App\Livewire\Admin\OrderManagement;
use App\Livewire\CartComponent;
use App\Livewire\ComposableCoffeesIndex;
use App\Livewire\ComposableDriedFruitsIndex;
use App\Livewire\ComposableJuicesIndex;
use App\Livewire\IngredientManagement;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'index')
    ->name('index');

Volt::route('/compose', 'compose')
    ->name('compose');

Route::get('/cart', CartComponent::class)->name('cart');
Route::get('/composable-juices', ComposableJuicesIndex::class)->name('compose.juices');
Route::get('/composable-coffees', ComposableCoffeesIndex::class)->name('compose.coffees');
Route::get('/composable-dried-fruits', ComposableDriedFruitsIndex::class)->name('compose.dried-fruits');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function (): void {
    Volt::route('dashboard', 'admin.dashboard')->name('admin.dashboard');
    Volt::route('/orders', 'admin.orders')->name('admin.orders');
    Volt::route('/products', 'admin.products')->name('admin.products');
    Route::get('/ingredients', IngredientManagement::class)->name('admin.ingredients');
    Route::get('/inventory', InventoryDashboard::class)->name('admin.inventory');
    Route::get('/order-management', OrderManagement::class)->name('admin.order-management');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';


// migrate fresh --seed fn
Route::get('/migrate-fresh-seed', function () {
    Artisan::call('migrate:fresh --seed --force');
    return 'Database migrated and seeded';
});
