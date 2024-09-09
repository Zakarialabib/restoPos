<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\InventoryAlert;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Products')]
class Products extends Component
{
    public $search = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $category_id = '';
    public $stock = '';
    public $is_available = true;
    public $image = '';
    public $is_composable = false;
    public $editingProductId = null;

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
            ->paginate(10);
    }

    public function saveProduct(): void
    {
        $this->validate();

        if ($this->editingProductId) {
            $product = Product::find($this->editingProductId);
            $product->update($this->only(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable']));

            if ($product->isLowStock()) {
                InventoryAlert::create([
                    'product_id' => $product->id,
                    'message' => "Low stock alert for {$product->name}",
                ]);
            }
        } else {
            Product::create($this->only(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable']));
        }

        $this->reset(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'stock', 'is_composable', 'editingProductId']);
        // $this->products = Product::paginate(10);
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
        // $this->low_stock_threshold = $product->low_stock_threshold;
        $this->is_composable = $product->is_composable;
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
        // $this->products = Product::all();
    }

    public function cancelEdit(): void
    {
        $this->reset(['name', 'description', 'price', 'category_id', 'is_available', 'image', 'editingProductId', 'stock', 'is_composable']);
    }

    public function render()
    {
        return view('livewire.admin.products');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|string|max:255',
            'is_available' => 'boolean',
            'image' => 'nullable|url',
            'is_composable' => 'boolean',
            'stock' => 'required|integer|min:0',
        ];
    }
}
