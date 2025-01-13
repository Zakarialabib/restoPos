<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enums\CategoryType;
use App\Livewire\Utils\Datatable;
use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

#[Layout('layouts.admin')]
#[Title('Category Management')]
class CategoryManagement extends Component
{
    use WithPagination;
    use Datatable;

    // Form Properties
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    public $status = true;
    public $is_composable = false;
    public $type = null;
    public $parent_id = null;

    // Filters
    public $type_filter = '';
    public $parent_filter = '';

    // UI States
    public $showForm = false;
    public $showAnalytics = true;
    public $editingId = null;
    public $showDeleteModal = false;
    public $targetCategoryId = null;

    public $model = Category::class;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'boolean',
        'is_composable' => 'boolean',
        'type' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',

    ];

    public function queryString()
    {
        return [
            'type_filter' => ['except' => ''],
            'parent_filter' => ['except' => ''],
        ];
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ])
            ->with(['parent'])
            ->when(
                $this->type_filter,
                fn($query, $type) =>
                $query->where('type', $type)
            )
            ->when(
                $this->parent_filter,
                fn($query, $parentId) =>
                $query->where('parent_id', $parentId)
            )
            ->paginate(10);
    }

    #[Computed]
    public function analytics()
    {
        return cache()->remember('category_analytics', now()->addMinutes(5), function () {
            $stats = collect(CategoryType::cases())->mapWithKeys(function ($type) {
                $query = Category::where('type', $type->value);
                return [
                    $type->value => [
                        'total' => $query->count(),
                        'active' => $query->where('status', true)->count(),
                        'label' => $type->label(),
                        'color' => $type->color(),
                        'icon' => $type->icon(),
                    ]
                ];
            });

            return [
                'types' => $stats,
                'total' => Category::count(),
                'active' => Category::where('status', true)->count(),
                'products' => Product::count(),
                'ingredients' => Ingredient::count(),
            ];
        });
    }

    #[Computed]
    public function categoryTypes()
    {
        return CategoryType::forSelect();
    }

    #[Computed]
    public function parentCategories()
    {
        return Category::query()
            ->whereNull('parent_id')
            ->where('status', true)
            ->when(
                $this->editingId,
                fn($query) =>
                $query->where('id', '!=', $this->editingId)
            )
            ->orderBy('name')
            ->get();
    }

    public function label(CategoryType $type): string
    {
        return $type->label();
    }

    public function color(CategoryType $type)
    {
        return $type->color();
    }

    public function icon(CategoryType $type)
    {
        return $type->icon();
    }

    public function saveCategory(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $categoryData = [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'status' => $this->status,
                'is_composable' => $this->is_composable,
                'type' => $this->type,
                'parent_id' => $this->parent_id,
            ];

            if ($this->editingId) {
                $category = Category::findOrFail($this->editingId);
                $category->update($categoryData);
            } else {
                Category::create($categoryData);
            }

            DB::commit();
            $this->reset();
            session()->flash('success', __('Category saved successfully.'));
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error saving category: ') . $e->getMessage());
        }
    }

    public function editCategory(Category $category): void
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->status = $category->status;
        $this->is_composable = $category->is_composable;
        $this->type = $category->type;
        $this->parent_id = $category->parent_id;
        $this->showForm = true;
    }

    public function deleteCategory(Category $category): void
    {
        if (!$category->canBeDeleted()) {
            session()->flash('error', __('Cannot delete category with existing items or subcategories.'));
            return;
        }

        $category->delete();
        session()->flash('success', __('Category deleted successfully.'));
    }


    public function toggleStatus(Category $category): void
    {
        $category->status = !$category->status;
        $category->save();
        session()->flash('success', __('Status updated successfully.'));
    }

    public function updateOrder(Category $category, int $newOrder): void
    {
        $category->updateSortOrder($newOrder);
        session()->flash('success', __('Order updated successfully.'));
    }

    public function render()
    {
        return view('livewire.admin.category-management');
    }
}
