<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryManagement extends Component
{
    public $categories;
    public $name;
    public $categoryId;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function saveCategory()
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

    public function editCategory($id)
    {
        $category = Category::find($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
    }

    public function deleteCategory($id)
    {
        Category::find($id)->delete();
        $this->categories = Category::all();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->categoryId = null;
    }

    public function render()
    {
        return view('livewire.category-management');
    }
}