<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\InventoryAlert;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Products')]
class Products extends Component
{
    use WithFileUploads;

    public $search = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $stock = '';
    public $is_available = true;
    public $image;
    public $is_composable = false;
    public $editingProductId = null;
    public $selectedIngredients = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer|min:0',
        'is_available' => 'boolean',
        // 'image' => 'nullable|string',
        'is_composable' => 'boolean',
        'selectedIngredients' => 'array',
        // 'selectedIngredients.*.id' => 'exists:ingredients,id',
        // 'selectedIngredients.*.stock' => 'required|numeric|min:0',
    ];

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query): void {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query): void {
                $query->where('category_id', $this->category_id);
            })
            ->where('is_available', true)
            ->with('ingredients')
            ->paginate(10);
    }


    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function ingredients()
    {
        return Ingredient::all();
    }

    public function saveProduct(): void
    {
        $this->validate();

        if ($this->editingProductId) {
            $product = Product::find($this->editingProductId);
            $filename = $this->name . '-' . $this->editingProductId . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('products', $filename, 'public');
            $this->image = $imagePath;
            
            $product->update($this->only(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable']));
        } else {

            $product = Product::create($this->only(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable']));
        }

        // Update ingredients
        $product->ingredients()->detach();
        foreach ($this->selectedIngredients as $ingredientData) {
            $product->ingredients()->attach($ingredientData['id'], ['stock' => $ingredientData['stock']]);
        }

        if ($product->isLowStock()) {
            InventoryAlert::create([
                'product_id' => $product->id,
                'message' => "Low stock alert for {$product->name}",
            ]);
        }

        $this->reset(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable', 'editingProductId', 'selectedIngredients']);
    }

    public function editProduct(Product $product): void
    {
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->is_available = $product->is_available;
        $this->image = $product->image;
        $this->stock = $product->stock;
        $this->is_composable = $product->is_composable;
        // $this->selectedIngredients = $product->ingredients->map(function ($ingredient) {
        //     return [
        //         'id' => $ingredient->id,
        //         'stock' => $ingredient->stock,
        //     ];
        // })->toArray();

        
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }

    public function cancelEdit(): void
    {
        $this->reset(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'editingProductId', 'stock', 'is_composable', 'selectedIngredients']);
    }

    
    // public function bulkDelete()
    // {
    //     Product::destroy($this->selectedProducts);
    //     $this->products = Product::all(); // Refresh the product list
    // }

    public function render()
    {
        return view('livewire.admin.products');
    }
}
