<?php

declare(strict_types=1);

use App\Livewire\Admin\CategoryManagement;
use App\Livewire\Admin\IngredientManagement;
use App\Livewire\Admin\OrderManagement;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\RecipeManagement;
use App\Livewire\ComposableDriedFruitsIndex;
use App\Livewire\ComposableJuicesIndex;
use App\Livewire\ComposableSaladeIndex;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'index')
    ->name('index');

Volt::route('/compose', 'compose')
    ->name('compose');

Route::get('/composable-juices', ComposableJuicesIndex::class)->name('compose.juices');
Route::get('/composable-salade', ComposableSaladeIndex::class)->name('compose.salade');
Route::get('/composable-dried-fruits', ComposableDriedFruitsIndex::class)->name('compose.dried-fruits');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function (): void {
    Volt::route('dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::get('/orders', OrderManagement::class)->name('admin.orders');
    Route::get('/products', ProductManagement::class)->name('admin.products');
    Route::get('/categories', CategoryManagement::class)->name('admin.categories');
    Route::get('/ingredients', IngredientManagement::class)->name('admin.ingredients');
    Route::get('/order-management', OrderManagement::class)->name('admin.order-management');
    Route::get('/recipes', RecipeManagement::class)->name('admin.recipes');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';


Route::get('/migrate-fresh-seed', function () {
    Artisan::call('migrate:fresh --seed --force');
    return 'Database migrated and seeded';
});

Route::get('/down', function () {
    Artisan::call('down');
    return 'Maintenance mode activated';
});

Route::get('/up', function () {
    Artisan::call('up');
    return 'Maintenance mode deactivated';
});
