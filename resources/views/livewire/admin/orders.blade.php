<?php

use function Livewire\Volt\{state, layout, rules, mount, computed, title, paginate};
use function Livewire\Volt\{with, usesPagination};
use App\Models\Order;
use App\Models\Product;

usesPagination();

layout('layouts.app');
title('Order Management');

with(
    fn() => [
        'orders' => Order::with('items')->paginate(10),
        'products' => Product::all(),
    ],
);

state([
    'editingOrderId' => null,
    'customerName' => '',
    'customerPhone' => '',
    'orderItems' => [],
    'totalAmount' => 0,
]);

$saveOrder = function () {
    $this->validate([
        'customerName' => 'required|string|max:255',
        'customerPhone' => 'required|string|max:255',
        'orderItems' => 'required|array|min:1',
        'totalAmount' => 'required|numeric|min:0',
    ]);

    if ($this->editingOrderId) {
        $order = Order::find($this->editingOrderId);
        $order->update([
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'total_amount' => $this->totalAmount,
        ]);
        $order->items()->delete();
    } else {
        $order = Order::create([
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'total_amount' => $this->totalAmount,
        ]);
    }

    foreach ($this->orderItems as $item) {
        $order->items()->create($item);
    }

    // $this->resetForm();
};

$editOrder = function (Order $order) {
    $this->editingOrderId = $order->id;
    $this->customerName = $order->customer_name;
    $this->customerPhone = $order->customer_phone;
    $this->orderItems = $order->items->toArray();
    $this->totalAmount = $order->total_amount;
};

$deleteOrder = function (Order $order) {
    $order->delete();
    // $this->resetForm();
};

?>
<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto bg-white">
            <div class="flex flex-wrap mt-4 px-2">
                <form wire:submit="saveOrder" class="w-1/2 space-y-4">
                    <x-accordion :title="$editingOrderId ? 'Edit Order' : 'Add New Order'" open="true">
                        <div>
                            <x-input-label for="customerName" :value="__('Customer Name')" />
                            <x-input type="text" wire:model="customerName" id="customerName" class="mt-1 block w-full"
                                required />
                            @error('customerName')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="customerPhone" :value="__('Customer Phone')" />
                            <x-input type="text" wire:model="customerPhone" id="customerPhone"
                                class="mt-1 block w-full" required />
                            @error('customerPhone')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="orderItems" :value="__('Order Items')" />
                            <div class="space-y-2">
                                @foreach ($orderItems as $index => $item)
                                    <div class="flex items-center space-x-2">
                                        <select wire:model="orderItems.{{ $index }}.product_id"
                                            class="mt-1 block w-full">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input type="number" wire:model="orderItems.{{ $index }}.quantity"
                                            class="mt-1 block w-full" placeholder="Quantity" required />
                                        <x-input type="number" wire:model="orderItems.{{ $index }}.price"
                                            class="mt-1 block w-full" placeholder="Price" required />
                                        <button type="button" wire:click="removeOrderItem({{ $index }})"
                                            class="text-red-500">Remove</button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="addOrderItem" class="text-blue-500">Add Item</button>
                            </div>
                            @error('orderItems')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="totalAmount" :value="__('Total Amount')" />
                            <x-input type="number" wire:model="totalAmount" id="totalAmount" class="mt-1 block w-full"
                                required />
                            @error('totalAmount')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end mt-2">
                            <x-button type="submit" color="primary">Save</x-button>
                        </div>
                    </x-accordion>
                </form>
                <div class="w-1/2 ">

                    <h3 class="text-2xl font-semibold mb-4 text-center">Order List</h3>
                    <div class="px-6">
                        <div
                            class="my-5 p-5 bg-white text-black text-base rounded-lg overflow-x-auto overflow-y-auto relative">
                            <table class="border-collapse table-auto w-full whitespace-no-wrap relative">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                            Name
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                            Amount
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($order->total_amount, 2) }} DH
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <x-button color="primary" size="sm" type="button"
                                                    wire:click="editOrder({{ $order->id }})"><span
                                                        class="material-icons">pen</span></x-button>
                                                <x-button color="danger" size="sm" type="button"
                                                    wire:click="deleteOrder({{ $order->id }})"><span
                                                        class="material-icons">trash</span></x-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
