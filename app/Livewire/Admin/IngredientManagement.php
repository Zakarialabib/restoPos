<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\Unit;
use App\Models\Category;
use App\Models\Ingredient;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class IngredientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
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
    ];

    #[Computed]
    public function ingredients()
    {
        return Ingredient::query()
            ->when(
                $this->search,
                fn ($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->type,
                fn ($query) =>
                $query->where('type', $this->type)
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
    public function types()
    {
        return ['fruit', 'liquid', 'ice'];
    }

    #[Computed]
    public function units()
    {
        return Unit::cases();
    }

    public function saveIngredient(): void
    {
        $this->validate();

        $ingredientData = [
            'name' => $this->name,
            'unit' => $this->unit,
            'conversion_rate' => $this->conversionRate,
            'stock' => $this->stock,
            'expiry_date' => $this->expiryDate,
            'supplier_info' => $this->supplierInfo,
            'instructions' => $this->instructions,
            'category_id' => $this->categoryId,
        ];

        if ($this->editingId) {
            Ingredient::find($this->editingId)->update($ingredientData);
        } else {
            Ingredient::create($ingredientData);
        }

        $this->reset();
        $this->showForm = false;
        session()->flash('message', __('Ingredient saved successfully.'));
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

        $this->showForm = true;
    }

    public function deleteIngredient(Ingredient $ingredient): void
    {
        $ingredient->delete();
        session()->flash('message', __('Ingredient deleted successfully.'));
    }

    public function updateStock(Ingredient $ingredient, float $quantity): void
    {
        $ingredient->updateStock($quantity);
        session()->flash('message', __('Stock updated successfully.'));
    }

    public function render()
    {
        return view('livewire.admin.ingredient-management');
    }
}
