<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

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

    public $type_filter = '';
    public $parent_filter = '';

    // UI States
    public $showForm = false;
    public $showAnalytics = false;
    public $editingId = null;
    public $showDeleteModal = false;
    public $targetCategoryId = null;

    #[Validate('required|string|max:255')]
    public $name = '';
    public $description = '';
    public $status = true;
    public $is_composable = false;
    public $type = null;
    public $parent_id = null;

    public $model = Category::class;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'boolean',
        'is_composable' => 'boolean',
        'type' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',

    ];

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->with(['parent'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ])
            ->when(
                $this->type_filter === 'product',
                fn($query) =>
                $query->forProducts()
            )
            ->when(
                $this->type_filter === 'ingredient',
                fn($query) =>
                $query->forIngredients()
            )
            ->when(
                $this->type_filter === 'composable',
                fn($query) =>
                $query->composable()
            )
            ->when(
                $this->parent_filter,
                fn($query) =>
                $query->where('parent_id', $this->parent_filter)
            )
            ->when(
                !$this->parent_filter,
                fn($query) =>
                $query->whereNull('parent_id')
            )->paginate(10);
    }

    #[Computed]
    public function parentCategories()
    {
        return Category::query()
            ->parentCategories()
            ->active()
            ->when(
                $this->editingId,
                fn($query) =>
                $query->where('id', '!=', $this->editingId)
            )
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function categoryAnalytics()
    {
        $categories = Category::all();

        $productCategories = $categories->where('type', Product::class);
        $ingredientCategories = $categories->where('type', Ingredient::class);

        $totalProducts = Product::count();
        $totalIngredients = Ingredient::count();

        return [
            'total_categories' => $categories->count(),
            'product_categories' => $productCategories->count(),
            'ingredient_categories' => $ingredientCategories->count(),
            'composable_categories' => $categories->where('is_composable', true)->count(),
            'total_products' => $totalProducts,
            'total_ingredients' => $totalIngredients,
            'active_categories' => $categories->where('status', true)->count(),
        ];
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
