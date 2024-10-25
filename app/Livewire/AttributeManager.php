<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\Category;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
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
    public $selectedIcon;

    public $translations = [];

    public $attributeList;

    public $editMode = false;

    protected $rules = [
//        'icon' => 'image',
        'translations.*.name' => 'required|string',
        'translations.*.symbol' => 'nullable|string',
    ];

    protected function rules()
    {
        $this->rules = RuleFactory::make([
            'icon' => 'required|image',
        ]);
    }

    public function mount()
    {
        $this->attributeList = Attribute::all();
    }

    public function render()
    {
        return view('livewire.attribute-manager', [
            'attributeList' => $this->attributeList,
            'categories' => Category::all(),
        ]);
    }

    // This method is triggered when the 'post-created' event is fired.
    // It listens for the event and receives the $selectedIcon value as a parameter.
    // Once triggered, it updates the $selectedIcon property with the new value.
    #[On('post-created')]
    public function postAdded($selectedIcon)
    {
        // Set the $selectedIcon property with the value passed from the event
        $this->selectedIcon = $selectedIcon;
    }

    public function storeAttribute()
    {

        $attribute = new Attribute();
//        if ($this->icon) {
//            $attribute->icon = $this->icon->store('attributes/icons', 'public');
//        }
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

    public function editAttribute($attributeId)
    {
        $this->editMode = true;

        $attribute = Attribute::findOrFail($attributeId);
        $this->attributeId = $attribute->id;
        $this->categoryId = $attribute->category_id;
        $this->type = $attribute->type;
        $this->required = $attribute->required;
        $this->icon = $attribute->icon;

        $this->translations = [];
        foreach ($attribute->translations as $translation) {
            $this->translations[$translation->locale] = [
                'name' => $translation->name,
                'symbol' => $translation->symbol,
            ];
        }
    }

    public function updateAttribute()
    {
        $this->validate();

        $attribute = Attribute::find($this->attributeId);

//        if ($this->icon && $this->icon instanceof \Livewire\TemporaryUploadedFile) {
//            $attribute->icon = $this->icon->store('attributes/icons', 'public');
//        }
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

        $this->editMode = false;
        $this->resetInputFields();
        $this->attributeList = Attribute::all();
    }

    public function deleteAttribute($attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);
//        if ($attribute->icon) {
//            Storage::delete($attribute->icon);
//        }
        $attribute->delete();
        $this->attributeList = Attribute::all();
    }

    private function resetInputFields()
    {
        $this->reset(['attributeId', 'icon', 'translations']);
    }
}
