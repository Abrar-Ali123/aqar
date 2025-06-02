<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryManager extends Component
{
    use WithFileUploads;

    public $category_id;
    public $parent_id = null;
    public $tempImage;
    public $translations = [
        'name' => [],
        'description' => []
    ];
    public $categories;
    public $editMode = false;
    public $isSubcategory = false;
    public $languages;

    protected $rules = [
        'translations.name.*' => 'required|min:2',
        'translations.description.*' => 'nullable',
        'parent_id' => 'nullable|exists:categories,id',
        'tempImage' => 'nullable|image|max:1024'
    ];

    protected $messages = [
        'translations.name.*.required' => 'حقل الاسم مطلوب لجميع اللغات',
        'translations.name.*.min' => 'يجب أن يكون الاسم أكثر من حرفين',
        'tempImage.image' => 'يجب أن يكون الملف صورة',
        'tempImage.max' => 'حجم الصورة يجب أن لا يتجاوز 1 ميجابايت'
    ];

    public function mount()
    {
        $this->categories = Category::with(['translations', 'parent.translations'])->get();
        $this->languages = config('app.locales');
    }

    public function render()
    {
        return view('livewire.category-manager');
    }

    public function storeCategory()
    {
        $this->validate();

        $category = new Category;
        $category->parent_id = $this->parent_id;
        
        if ($this->tempImage) {
            $category->image = $this->tempImage->store('categories', 'public');
        }

        $category->save();

        // حفظ الترجمات
        foreach ($this->translations['name'] as $locale => $name) {
            $category->setTranslation('name', $locale, $name);
        }

        foreach ($this->translations['description'] as $locale => $description) {
            if ($description) {
                $category->setTranslation('description', $locale, $description);
            }
        }

        $category->save();

        $this->categories = Category::with(['translations', 'parent.translations'])->get();
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'تم إضافة الفئة بنجاح']);
    }

    public function updateSubcategory()
    {
        if (!$this->isSubcategory) {
            $this->parent_id = null;
        }
    }

    public function editCategory($categoryId)
    {
        $category = Category::with('translations')->findOrFail($categoryId);
        
        $this->category_id = $category->id;
        $this->parent_id = $category->parent_id;
        $this->isSubcategory = $category->parent_id !== null;
        
        // تحميل الترجمات
        foreach (config('app.locales') as $locale) {
            $this->translations['name'][$locale] = $category->getTranslation('name', $locale, '');
            $this->translations['description'][$locale] = $category->getTranslation('description', $locale, '');
        }

        $this->editMode = true;
    }

    public function updateCategory()
    {
        $this->validate();

        $category = Category::findOrFail($this->category_id);
        $category->parent_id = $this->parent_id;

        if ($this->tempImage) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $this->tempImage->store('categories', 'public');
        }

        // تحديث الترجمات
        foreach ($this->translations['name'] as $locale => $name) {
            $category->setTranslation('name', $locale, $name);
        }

        foreach ($this->translations['description'] as $locale => $description) {
            if ($description) {
                $category->setTranslation('description', $locale, $description);
            }
        }

        $category->save();

        $this->categories = Category::with(['translations', 'parent.translations'])->get();
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'تم تحديث الفئة بنجاح']);
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        // التحقق من وجود فئات فرعية
        if ($category->children()->count() > 0) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'لا يمكن حذف هذه الفئة لأنها تحتوي على فئات فرعية'
            ]);
            return;
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        $this->categories = Category::with(['translations', 'parent.translations'])->get();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'تم حذف الفئة بنجاح']);
    }

    private function resetInputFields()
    {
        $this->reset([
            'category_id',
            'parent_id',
            'tempImage',
            'translations',
            'editMode',
            'isSubcategory'
        ]);
        
        $this->translations = [
            'name' => [],
            'description' => []
        ];
    }
}
