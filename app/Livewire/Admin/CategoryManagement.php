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

    #[Computed]
    public function categories()
    {
        return Category::query()->orderBy('name')->get();
    }

    public function saveCategory(): void
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            ['name' => $this->name]
        );

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
