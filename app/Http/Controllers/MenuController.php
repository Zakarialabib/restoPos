<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function tvMenu()
    {
        $products = ProductResource::collection(Product::query()->whereStatus(true)->with('category')->get());

        return Inertia::render('Menu/TVMenu', [
            'products' => $products,
        ]);
    }

    public function index()
    {
        $products = Product::with([
            'category',
        ])
            ->where('status', true)
            ->get()
            ->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'ingredients' => $product->ingredients,
                    'image' => asset("images/{$product->image}") ?? null,
                    'category_id' => $product->category_id,
                    'status' => $product->status,
                    'prices' => $product->prices->map(fn($price) => [
                        'size' => $price->metadata['size'],
                        'price' => number_format($price->price, 2)
                    ]),
                ];
            });

        $categories = Category::select('id', 'name', 'status')->where('status', true)->get();

        return Inertia::render('Menu/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
