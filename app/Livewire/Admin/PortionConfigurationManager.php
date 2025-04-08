<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\PortionConfiguration;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PortionConfigurationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $editing = null;
    public $category_id = '';
    public $name = '';
    public $type = '';
    public $sizes = [];
    public $addons = [];
    public $sides = [];
    public $upgrades = [];
    public $is_active = true;

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'type' => 'required|string|in:burger,pizza,drink,fries,combo',
        'sizes' => 'required|array',
        'sizes.*' => 'required|string',
        'addons' => 'nullable|array',
        'addons.*' => 'required|string',
        'sides' => 'nullable|array',
        'sides.*' => 'required|string',
        'upgrades' => 'nullable|array',
        'upgrades.*' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function render(): View
    {
        $configurations = PortionConfiguration::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = Category::where('type', 'product')->get();

        return view('livewire.admin.portion-configuration-manager', [
            'configurations' => $configurations,
            'categories' => $categories,
        ]);
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editing = null;
    }

    public function edit(PortionConfiguration $configuration): void
    {
        $this->editing = $configuration;
        $this->category_id = $configuration->category_id;
        $this->name = $configuration->name;
        $this->type = $configuration->type;
        $this->sizes = $configuration->sizes;
        $this->addons = $configuration->addons;
        $this->sides = $configuration->sides;
        $this->upgrades = $configuration->upgrades;
        $this->is_active = $configuration->is_active;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'type' => $this->type,
            'sizes' => $this->sizes,
            'addons' => $this->addons,
            'sides' => $this->sides,
            'upgrades' => $this->upgrades,
            'is_active' => $this->is_active,
        ];

        if ($this->editing) {
            $this->editing->update($data);
        } else {
            PortionConfiguration::create($data);
        }

        $this->resetForm();
        $this->dispatch('notify', [
            'message' => 'Portion configuration saved successfully.',
            'type' => 'success',
        ]);
    }

    public function delete(PortionConfiguration $configuration): void
    {
        $configuration->delete();
        $this->dispatch('notify', [
            'message' => 'Portion configuration deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function toggleStatus(PortionConfiguration $configuration): void
    {
        $configuration->update(['is_active' => !$configuration->is_active]);
        $this->dispatch('notify', [
            'message' => 'Portion configuration status updated successfully.',
            'type' => 'success',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'category_id',
            'name',
            'type',
            'sizes',
            'addons',
            'sides',
            'upgrades',
            'is_active',
        ]);
    }

    public function addSize(): void
    {
        $this->sizes[] = '';
    }

    public function removeSize(int $index): void
    {
        unset($this->sizes[$index]);
        $this->sizes = array_values($this->sizes);
    }

    public function addAddon(): void
    {
        $this->addons[] = '';
    }

    public function removeAddon(int $index): void
    {
        unset($this->addons[$index]);
        $this->addons = array_values($this->addons);
    }

    public function addSide(): void
    {
        $this->sides[] = '';
    }

    public function removeSide(int $index): void
    {
        unset($this->sides[$index]);
        $this->sides = array_values($this->sides);
    }

    public function addUpgrade(): void
    {
        $this->upgrades[] = '';
    }

    public function removeUpgrade(int $index): void
    {
        unset($this->upgrades[$index]);
        $this->upgrades = array_values($this->upgrades);
    }
} 