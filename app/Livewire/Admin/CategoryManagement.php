<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Category Management')]
class CategoryManagement extends Component
{
    use WithPagination;

    #[Validate('required|string|max:255')]
    public $name;

    public $categoryId;
    public string $search = '';

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    public function saveCategory()
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                // 'description' => $this->description,
                'status' => $this->status,
            ]
        );

        session()->flash('message', $this->categoryId ? __('Category updated successfully.') : __('Category added successfully.'));
        $this->resetForm();
    }

    public function editCategory($id): void
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
    }

    public function deleteCategory($id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', __('Category deleted successfully.'));
    }


    public function toggleStatus(int $id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        session()->flash('message', __('Category status updated successfully.'));
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->categoryId = null;
    }

    public function render()
    {
        return view('livewire.admin.category-management');
    }
}
