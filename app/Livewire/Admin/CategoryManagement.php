<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Category Management')]
class CategoryManagement extends Component
{
    use WithPagination;

    public $categories;

    #[Validate('required|string|max:255')]
    public $name;

    public $categoryId;

    public function mount(): void
    {
        $this->categories = Category::all();
    }

    public function saveCategory(): void
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            $category->update(['name' => $this->name]);
        } else {
            Category::create(['name' => $this->name]);
        }

        $this->resetForm();
        $this->categories = Category::all();
    }

    public function editCategory($id): void
    {
        $category = Category::find($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
    }

    public function deleteCategory($id): void
    {
        Category::find($id)->delete();
        $this->categories = Category::all();
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
