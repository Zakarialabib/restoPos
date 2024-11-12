<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderProcessor
{
    public function process(array $cart, string $customerName, string $customerPhone): Order
    {
        return DB::transaction(function () use ($cart, $customerName, $customerPhone) {
            $order = Order::create([
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'status' => OrderStatus::Pending,
                'total' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity'])
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'details' => $item['ingredients'],
                ]);
            }

            return $order;
        });
    }
}
