<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class AttributeManager extends Component
{
    use WithFileUploads;

    public $type;

    public $required;

    public $categoryId;

    public $attributeId;

    public $icon;

    public $selectedIcon = '';  // الأيقونة المختارة

    public $translations = [];

    public $attributeList;

    public $editMode = false;

    public function mount()
    {
        $this->attributeList = Attribute::all();
    }

    // تحديث الأيقونة المختارة
    public function selectIcon($icon)
    {
        $this->selectedIcon = $icon;
    }

    public function storeAttribute()
    {
        $attribute = new Attribute;
        if ($this->selectedIcon) {
            $attribute->icon = $this->selectedIcon;
        }

        $attribute->category_id = $this->categoryId;
        $attribute->type = $this->type;
        $attribute->required = $this->required;
        $attribute->save();

        foreach ($this->translations as $locale => $translation) {
            $attribute->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $translation['name'],
                    'symbol' => $translation['symbol'] ?? null,
                ]
            );
        }

        $this->attributeList = Attribute::all();
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['attributeId', 'icon', 'translations', 'selectedIcon']);
    }

    public function render()
    {
        return view('livewire.attribute-manager', [
            'attributeList' => $this->attributeList,
            'categories' => Category::all(),
        ]);
    }
}
