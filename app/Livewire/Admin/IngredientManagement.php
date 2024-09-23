<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

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
        'stock' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
        'batch_number' => 'nullable|string|max:255',
        'expiry_date' => 'nullable|date|after:today',
        'volume' => 'nullable|integer', // Add volume validation
        'instructions' => 'nullable|string', // Add instructions validation
    ];

    public function mount(): void
    {
        $this->loadProducts();
        $this->categories = Category::all();
        $this->ingredients = Ingredient::all();
    }

    public function loadProducts(): void
    {
        $this->products = Product::active()->with('category', 'ingredients')->get(); // Use active scope
    }

    public function addProduct(): void
    {
        $this->validate();
        // ... add product logic
        $this->emit('productAdded'); // Emit event for real-time updates
    }

    public function editProduct($productId): void
    {
        $this->editingProduct = Product::with('ingredients')->find($productId);
    }

    public function updateProduct(): void
    {
        $this->validate();
        $this->editingProduct->save();
        if (isset($this->editingProduct['ingredients'])) {
            $this->editingProduct->ingredients()->sync($this->editingProduct['ingredients']);
        }
        $this->editingProduct = null;
        $this->loadProducts();
    }

    public function logIngredientUsage($ingredientId, $quantity)
    {
        // Logic to log ingredient usage
    }

    public function checkReorderAlerts()
    {
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->isLowStock()) { // Use the new method
                // Notify admin
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.ingredient-management');
    }
}
