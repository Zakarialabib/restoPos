<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\CartComponent;
use App\Livewire\CheckoutComponent;
use App\Livewire\ComposableJuicesIndex;
// use App\Livewire\ComposableJuiceDetails;

Volt::route('/', 'index')
    ->name('index');

Volt::route('/compose', 'compose')
    ->name('compose');

Route::get('/cart', CartComponent::class)->name('cart');
Route::get('/checkout', CheckoutComponent::class)->name('checkout');
Route::get('/composable-juices', ComposableJuicesIndex::class)->name('composable-juices.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('/products', 'admin.products')
    ->middleware(['auth', 'verified'])
    ->name('admin.products');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
