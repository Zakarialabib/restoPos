<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function track(Order $order)
    {
        $order->load('items.product'); // Load the order items and their products
        return Inertia::render('Orders/Track', [
            'order' => $order,
        ]);
    }
}
