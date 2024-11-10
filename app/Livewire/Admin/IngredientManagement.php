<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\Unit;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Price;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class IngredientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';
    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name = '';
    public $unit;
    public $conversionRate = 1;
    public $stock = 0;
    public $expiryDate = '';
    public $supplierInfo = [];
    public $instructions = [];
    public $storageConditions = [];
    public $categoryId = '';
    public $cost = 0;
    public $price = 0;
    public $nutritionalInfo = [];

    // Add new properties for price history
    public $showPriceHistory = false;
    public $selectedIngredientId;
    public $newPrice = [
        'cost' => 0,
        'price' => 0,
        'notes' => '',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'unit' => 'required',
        'conversionRate' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:0',
        'expiryDate' => 'nullable|date|after:today',
        'supplierInfo' => 'nullable|array',
        'instructions' => 'nullable|array',
        'storageConditions' => 'nullable|array',
        'categoryId' => 'required|exists:categories,id',
        'cost' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'nutritionalInfo' => 'nullable|array',
        'newPrice.cost' => 'required|numeric|min:0',
        'newPrice.price' => 'required|numeric|min:0',
        'newPrice.notes' => 'nullable|string|max:255',
    ];

    #[Computed]
    public function ingredients()
    {
        return Ingredient::query()
            ->with('category')
            ->when($this->search, fn ($query) => 
                $query->where('name', 'like', '%' . $this->search . '%')
            )
            ->when($this->category_id, fn ($query) => 
                $query->where('category_id', $this->category_id)
            )
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->get();
    }

    #[Computed]
    public function units()
    {
        return Unit::cases();
    }

    public function saveIngredient(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $ingredientData = [
                'name' => $this->name,
                'unit' => $this->unit,
                'conversion_rate' => $this->conversionRate,
                'stock' => $this->stock,
                'expiry_date' => $this->expiryDate,
                'supplier_info' => $this->supplierInfo,
                'instructions' => $this->instructions,
                'storage_conditions' => $this->storageConditions,
                'category_id' => $this->categoryId,
                'nutritional_info' => $this->nutritionalInfo,
            ];

            if ($this->editingId) {
                $ingredient = Ingredient::find($this->editingId);
                $ingredient->update($ingredientData);
            } else {
                $ingredient = Ingredient::create($ingredientData);
            }

            // Add initial price record
            if ($this->cost > 0 || $this->price > 0) {
                $ingredient->addPrice($this->cost, $this->price, 'Initial price');
            }

            DB::commit();
            $this->reset();
            $this->showForm = false;
            session()->flash('success', __('Ingredient saved successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error saving ingredient.'));
        }
    }

    public function editIngredient(Ingredient $ingredient): void
    {
        $this->editingId = $ingredient->id;
        $this->name = $ingredient->name;
        $this->unit = $ingredient->unit->value;
        $this->conversionRate = $ingredient->conversion_rate;
        $this->stock = $ingredient->stock;
        $this->expiryDate = $ingredient->expiry_date?->format('Y-m-d');
        $this->categoryId = $ingredient->category_id;
        $this->supplierInfo = $ingredient->supplier_info;
        $this->instructions = $ingredient->instructions;
        $this->storageConditions = $ingredient->storage_conditions;
        $this->cost = $ingredient->cost;
        $this->price = $ingredient->price;
        $this->nutritionalInfo = $ingredient->nutritional_info;

        $this->showForm = true;
    }

    public function deleteIngredient(Ingredient $ingredient): void
    {
        if ($ingredient->products()->exists()) {
            session()->flash('error', __('Cannot delete ingredient used in products.'));
            return;
        }

        $ingredient->delete();
        session()->flash('success', __('Ingredient deleted successfully.'));
    }

    public function updateStock(Ingredient $ingredient, float $quantity, string $reason = 'Manual Update'): void
    {
        try {
            $ingredient->updateStock($quantity, $reason);
            session()->flash('success', __('Stock updated successfully.'));
        } catch (\Exception $e) {
            session()->flash('error', __('Error updating stock.'));
        }
    }

    public function showPriceHistoryModal(int $ingredientId): void
    {
        $this->selectedIngredientId = $ingredientId;
        $this->showPriceHistory = true;
    }

    public function addNewPrice(Ingredient $ingredient): void
    {
        $this->validate([
            'newPrice.cost' => 'required|numeric|min:0',
            'newPrice.price' => 'required|numeric|min:0',
        ]);

        try {
            $ingredient->addPrice(
                $this->newPrice['cost'],
                $this->newPrice['price'],
                $this->newPrice['notes']
            );

            $this->reset('newPrice');
            session()->flash('success', __('Price updated successfully.'));
        } catch (\Exception $e) {
            session()->flash('error', __('Error updating price.'));
        }
    }

    #[Computed]
    public function priceHistory()
    {
        if (!$this->selectedIngredientId) {
            return collect();
        }

        return Ingredient::find($this->selectedIngredientId)
            ->getPriceHistory()
            ->map(function ($price) {
                return [
                    'date' => $price->date->format('Y-m-d'),
                    'cost' => $price->cost,
                    'price' => $price->price,
                    'notes' => $price->notes,
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.ingredient-management');
    }
}
