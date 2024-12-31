<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.size' => 'nullable|string',
                'items.*.price' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:cash,card',
                'order_type' => 'required|string|in:in-store'
            ]);

            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'total_amount' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'order_type' => $validated['order_type']
            ]);

            // Create order items
            $orderItems = collect($validated['items'])->map(function ($item) {
                return [
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'size' => $item['size'] ?? null,
                ];
            })->all();

            $order->items()->createMany($orderItems);

            DB::commit();

            Log::info('Order placed successfully', [
                'order_id' => $order->id,
                'total' => $validated['total']
            ]);

            return back()->with([
                'success' => 'Order placed successfully! Please wait for confirmation.',
                'order' => $order, // Return the order object
            ]);
          
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place order: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
}
