<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManagement extends Component
{
    public $categories;
    public $name;
    public $categoryId;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

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
        return view('livewire.category-management');
    }
}
