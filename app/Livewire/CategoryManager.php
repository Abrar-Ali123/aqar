<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryManager extends Component
{
    use WithFileUploads;

    protected $rules = [];

    public $category_id;

    public $name;

    public $parent_id = null;

    public $tempImage;

    public $translations = [];

    public $categories;

    public $editMode = false;

    public $isSubcategory = false;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function storeCategory()
    {

        $category = new Category;
        $category->parent_id = $this->parent_id;
        if ($this->tempImage) {
            $category->image = $this->tempImage->store('categories', 'public');
        }

        $category->save();

        // حفظ الترجمات
        foreach ($this->translations as $locale => $name) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $name]
            );
        }

        $this->categories = Category::with('translations')->get();
        $this->resetInputFields();
    }

    public function updateSubcategory()
    {
        if (! $this->isSubcategory) {
            $this->parent_id = null;
        }
    }

    public function editCategory($categoryId)
    {
        $this->editingCategory = $categoryId;

        $category = Category::with('translations')->findOrFail($categoryId);
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->image = $category->image;

        $this->translations = [];
        foreach ($category->translations as $translation) {
            $this->translations[$translation->locale] = $translation->name;
        }

        $this->editMode = true;
    }

    public function updateCategory()
    {
        $this->Rules();
        $this->validate($this->rules);

        $category = Category::findOrFail($this->category_id);
        $category->name = $this->name;
        $category->parent_id = $this->parent_id;

        if ($this->tempImage) {
            if ($category->image) {
                Storage::delete($category->image);
            }
            $category->image = $this->tempImage->store('categories', 'public');
        }

        foreach ($this->translations as $locale => $name) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $name]
            );
        }
        $category->save();

        $this->categories = Category::with('translations')->get();
        $this->resetInputFields();
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        if ($category->image) {
            Storage::delete($category->image);
        }
        $category->delete();
        $this->categories = Category::with('translations')->get();
    }

    private function resetInputFields()
    {
        $this->reset(['category_id', 'name', 'parent_id', 'tempImage', 'editMode']);
    }
}
