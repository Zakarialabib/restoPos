<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products\Composable;

use App\Models\Category;
use App\Models\ComposableConfiguration;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Composable Configuration Manager')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortField = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    public bool $showModal = false;
    public ?ComposableConfiguration $editing = null;

    public array $form = [
        'category_id' => '',
        'name' => '',
        'description' => '',
        'has_base' => false,
        'has_sugar' => false,
        'has_size' => false,
        'has_addons' => false,
        'min_ingredients' => 1,
        'max_ingredients' => null,
        'sizes' => [],
        'base_types' => [],
        'sugar_types' => [],
        'addon_types' => [],
        'icons' => [
            'base' => '',
            'sugar' => '',
            'size' => '',
            'addons' => '',
        ],
        'is_active' => true,
    ];

    protected function rules(): array
    {
        return [
            'form.category_id' => 'required|exists:categories,id',
            'form.name' => 'required|string|max:255',
            'form.description' => 'nullable|string',
            'form.has_base' => 'boolean',
            'form.has_sugar' => 'boolean',
            'form.has_size' => 'boolean',
            'form.has_addons' => 'boolean',
            'form.min_ingredients' => 'required|integer|min:1',
            'form.max_ingredients' => 'nullable|integer|min:1|gte:form.min_ingredients',
            'form.sizes' => 'required_if:form.has_size,true|array',
            'form.sizes.*' => 'required|string',
            'form.base_types' => 'required_if:form.has_base,true|array',
            'form.base_types.*' => 'required|string',
            'form.sugar_types' => 'required_if:form.has_sugar,true|array',
            'form.sugar_types.*' => 'required|string',
            'form.addon_types' => 'required_if:form.has_addons,true|array',
            'form.addon_types.*' => 'required|string',
            'form.icons.base' => 'required_if:form.has_base,true|string',
            'form.icons.sugar' => 'required_if:form.has_sugar,true|string',
            'form.icons.size' => 'required_if:form.has_size,true|string',
            'form.icons.addons' => 'required_if:form.has_addons,true|string',
            'form.is_active' => 'boolean',
        ];
    }

    public function render(): View
    {
        $configurations = ComposableConfiguration::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $categories = Category::where('type', 'product')->get();

        return view('livewire.admin.products.composable.index', [
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
        $this->showModal = true;
    }

    public function edit(ComposableConfiguration $configuration): void
    {
        $this->editing = $configuration;
        $this->form = [
            'category_id' => $configuration->category_id,
            'name' => $configuration->name,
            'description' => $configuration->description,
            'has_base' => $configuration->has_base,
            'has_sugar' => $configuration->has_sugar,
            'has_size' => $configuration->has_size,
            'has_addons' => $configuration->has_addons,
            'min_ingredients' => $configuration->min_ingredients,
            'max_ingredients' => $configuration->max_ingredients,
            'sizes' => $configuration->sizes ?? [],
            'base_types' => $configuration->base_types ?? [],
            'sugar_types' => $configuration->sugar_types ?? [],
            'addon_types' => $configuration->addon_types ?? [],
            'icons' => $configuration->icons ?? [
                'base' => '',
                'sugar' => '',
                'size' => '',
                'addons' => '',
            ],
            'is_active' => $configuration->is_active,
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->editing) {
                $this->editing->update($this->form);
            } else {
                ComposableConfiguration::create($this->form);
            }
        });

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => 'Configuration saved successfully.',
            'type' => 'success',
        ]);
    }

    public function delete(ComposableConfiguration $configuration): void
    {
        $configuration->delete();
        $this->dispatch('notify', [
            'message' => 'Configuration deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function toggleStatus(ComposableConfiguration $configuration): void
    {
        $configuration->update(['is_active' => !$configuration->is_active]);
        $this->dispatch('notify', [
            'message' => 'Configuration status updated successfully.',
            'type' => 'success',
        ]);
    }

    private function resetForm(): void
    {
        $this->form = [
            'category_id' => '',
            'name' => '',
            'description' => '',
            'has_base' => false,
            'has_sugar' => false,
            'has_size' => false,
            'has_addons' => false,
            'min_ingredients' => 1,
            'max_ingredients' => null,
            'sizes' => [],
            'base_types' => [],
            'sugar_types' => [],
            'addon_types' => [],
            'icons' => [
                'base' => '',
                'sugar' => '',
                'size' => '',
                'addons' => '',
            ],
            'is_active' => true,
        ];
    }

    public function addSize(): void
    {
        $this->form['sizes'][] = '';
    }

    public function removeSize(int $index): void
    {
        unset($this->form['sizes'][$index]);
        $this->form['sizes'] = array_values($this->form['sizes']);
    }

    public function addBaseType(): void
    {
        $this->form['base_types'][] = '';
    }

    public function removeBaseType(int $index): void
    {
        unset($this->form['base_types'][$index]);
        $this->form['base_types'] = array_values($this->form['base_types']);
    }

    public function addSugarType(): void
    {
        $this->form['sugar_types'][] = '';
    }

    public function removeSugarType(int $index): void
    {
        unset($this->form['sugar_types'][$index]);
        $this->form['sugar_types'] = array_values($this->form['sugar_types']);
    }

    public function addAddonType(): void
    {
        $this->form['addon_types'][] = '';
    }

    public function removeAddonType(int $index): void
    {
        unset($this->form['addon_types'][$index]);
        $this->form['addon_types'] = array_values($this->form['addon_types']);
    }
}
