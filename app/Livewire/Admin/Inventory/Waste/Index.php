<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Inventory\Waste;

use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Waste Management')]
class Index extends Component
{
    use WithPagination;

    public array $selectedIngredients = [];
    public string $search = '';
    public ?string $categoryId = null;
    public ?string $status = null;
    public bool $showExpiredOnly = false;

    public function mount(): void
    {
        $this->loadIngredients();
    }

    public function loadIngredients(): Collection
    {
        return Ingredient::query()
            ->with('category')
            ->when($this->search, function ($query): void {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->categoryId, function ($query): void {
                $query->where('category_id', $this->categoryId);
            })
            ->when($this->showExpiredOnly, function ($query): void {
                $query->where('expiry_date', '<', now());
            })
            ->orderBy('expiry_date', 'asc')
            ->get();
    }

    public function getIngredientStatus(Ingredient $ingredient): string
    {
        if ($ingredient->expiry_date < now()) {
            return 'expired';
        }

        if ($ingredient->expiry_date->diffInDays(now()) <= 3) {
            return 'warning';
        }

        return 'safe';
    }

    public function getStatusColor(string $status): string
    {
        return match ($status) {
            'expired' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            'safe' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusText(string $status): string
    {
        return match ($status) {
            'expired' => 'Expired',
            'warning' => 'Expiring Soon',
            'safe' => 'Safe',
            default => 'Unknown',
        };
    }

    public function toggleIngredient(string $ingredientId): void
    {
        if (in_array($ingredientId, $this->selectedIngredients)) {
            $this->selectedIngredients = array_diff($this->selectedIngredients, [$ingredientId]);
        } else {
            $this->selectedIngredients[] = $ingredientId;
        }
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function render(): \Illuminate\View\View
    {
        $ingredients = $this->loadIngredients();
        $stats = [
            'total' => $ingredients->count(),
            'expired' => $ingredients->filter(fn ($i) => 'expired' === $this->getIngredientStatus($i))->count(),
            'warning' => $ingredients->filter(fn ($i) => 'warning' === $this->getIngredientStatus($i))->count(),
            'safe' => $ingredients->filter(fn ($i) => 'safe' === $this->getIngredientStatus($i))->count(),
        ];

        return view('livewire.admin.inventory.waste.index', [
            'ingredients' => $ingredients,
            'stats' => $stats,
        ]);
    }
}
