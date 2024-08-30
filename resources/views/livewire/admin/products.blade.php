<?php

use App\Models\Product;
use function Livewire\Volt\{state, layout, rules, computed, title, paginate};
use function Livewire\Volt\{with, usesPagination};

usesPagination();

with(
    fn() => [
        'products' => Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->where('is_available', true)
            ->get(),
    ],
);

layout('layouts.app');
title('Products');

state([
    'search' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'category' => '',
    'is_available' => true,
    'image_url' => '',
    'is_composable' => false,
    'editingProductId' => null,
]);

rules([
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'category' => 'required|string|max:255',
    'is_available' => 'boolean',
    'image_url' => 'nullable|url',
]);

$saveProduct = function () {
    $this->validate();

    if ($this->editingProductId) {
        $product = Product::find($this->editingProductId);
        $product->update($this->only(['name', 'description', 'price', 'category', 'is_available', 'image_url']));
    } else {
        Product::create($this->only(['name', 'description', 'price', 'category', 'is_available', 'image_url']));
    }

    $this->reset(['name', 'description', 'price', 'category', 'is_available', 'image_url', 'editingProductId']);
    $this->products = Product::paginate(10);
};

$editProduct = function (Product $product) {
    $this->editingProductId = $product->id;
    $this->name = $product->name;
    $this->description = $product->description;
    $this->price = $product->price;
    $this->category = $product->category;
    $this->is_available = $product->is_available;
    $this->image_url = $product->image_url;
};

$deleteProduct = function (Product $product) {
    $product->delete();
    $this->products = Product::all();
};

$cancelEdit = function () {
    $this->reset(['name', 'description', 'price', 'category', 'is_available', 'image_url', 'editingProductId']);
};

?>

<div class="py-12">
    <div class="max-w-7xl mx-auto bg-white">
        <div class="flex flex-wrap mt-4 px-2">

            <form wire:submit="saveProduct" class="w-1/4">
                <x-accordion>
                    <x-slot name="title">
                        {{ $editingProductId ? 'Edit Product' : 'Add New Product' }}
                    </x-slot>
                    <div class="space-y-4 px-4">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-input wire:model="name" id="name" class="block mt-1 w-full" type="text"
                                name="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea wire:model="description" id="description" name="description"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-input wire:model="price" id="price" class="block mt-1 w-full" type="number"
                                name="price" step="0.01" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <x-input wire:model="category" id="category" class="block mt-1 w-full" type="text"
                                name="category" required />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div>
                            <label for="is_available" class="inline-flex items-center">
                                <input wire:model="is_available" id="is_available" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="is_available">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Available') }}</span>
                            </label>
                        </div>

                        <div>
                            <x-input-label for="image_url" :value="__('Image URL')" />
                            <x-input wire:model="image_url" id="image_url" class="block mt-1 w-full" type="url"
                                name="image_url" />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end  px-4 mt-4">
                        @if ($editingProductId)
                            <x-secondary-button class="mr-3" wire:click="cancelEdit">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        @endif
                        <x-primary-button>
                            {{ $editingProductId ? __('Update Product') : __('Add Product') }}
                        </x-primary-button>
                    </div>
                </x-accordion>

            </form>

            <div class="w-3/4 ">
                <h2 class="text-2xl font-semibold mb-4 text-center">Product List</h2>
                <div class="px-6">
                    <x-table>
                        <x-slot name="header">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Available</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </x-slot>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->is_available)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="editProduct({{ $product->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                            Edit
                                        </button>
                                        <x-danger-button wire:click="deleteProduct({{ $product->id }})">
                                            Delete
                                        </x-danger-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">No products
                                        found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </x-table>

                    {{-- pagination --}}
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
