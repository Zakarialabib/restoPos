<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\CartComponent;
use App\Livewire\ComposableJuicesIndex;
// use App\Livewire\ComposableJuiceDetails;

Volt::route('/', 'index')
    ->name('index');

Volt::route('/compose', 'compose')
    ->name('compose');

Route::get('/cart', CartComponent::class)->name('cart');
Route::get('/composable-juices', ComposableJuicesIndex::class)->name('composable-juices.index');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'admin.dashboard')->name('admin.dashboard');
    Volt::route('/orders', 'admin.orders')->name('admin.orders');
    Volt::route('/products', 'admin.products')->name('admin.products');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
