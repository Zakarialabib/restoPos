<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Cart/Index');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'options' => 'nullable|array'
        ]);

        // Logic to add item to cart will be handled by the frontend
        return response()->json(['message' => 'Item added to cart']);
    }

    public function remove($id)
    {
        // Logic to remove item from cart will be handled by the frontend
        return response()->json(['message' => 'Item removed from cart']);
    }
} 