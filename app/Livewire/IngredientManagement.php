<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Livewire\Component;

class IngredientManagement extends Component
{
    public $products;
    public $categories;
    public $ingredients;
    public $newProduct = [];
    public $editingProduct = null;

    public static $rules = [
        'name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
        'batch_number' => 'nullable|string|max:255',
        'expiry_date' => 'nullable|date|after:today',
    ];
    
    public function mount(): void
    {
        $this->loadProducts();
        $this->categories = Category::all();
        $this->ingredients = Ingredient::all();
    }

    public function loadProducts(): void
    {
        $this->products = Product::with('category', 'ingredients')->get();
    }

    public function addProduct(): void
    {
        $product = Product::create($this->newProduct);
        if (isset($this->newProduct['ingredients'])) {
            $product->ingredients()->sync($this->newProduct['ingredients']);
        }
        $this->newProduct = [];
        $this->loadProducts();
    }

    public function editProduct($productId): void
    {
        $this->editingProduct = Product::with('ingredients')->find($productId);
    }

    public function updateProduct(): void
    {
        $this->editingProduct->save();
        if (isset($this->editingProduct['ingredients'])) {
            $this->editingProduct->ingredients()->sync($this->editingProduct['ingredients']);
        }
        $this->editingProduct = null;
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.ingredient-management');
    }
}
