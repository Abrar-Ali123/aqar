<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageSectionContentManager extends Component
{
    public $page;
    public $section;
    public $items = [];
    public $newItem = [];
    public $editingIndex = null;

    public function mount($page, $section)
    {
        $this->page = $page;
        $this->section = $section;
        $this->items = $page->$section ?? [];
    }

    public function addItem()
    {
        $this->items[] = $this->newItem;
        $this->newItem = [];
        $this->saveItems();
    }

    public function editItem($i)
    {
        $this->editingIndex = $i;
        $this->newItem = $this->items[$i];
    }

    public function updateItem()
    {
        if (!is_null($this->editingIndex)) {
            $this->items[$this->editingIndex] = $this->newItem;
            $this->editingIndex = null;
            $this->newItem = [];
            $this->saveItems();
        }
    }

    public function deleteItem($i)
    {
        array_splice($this->items, $i, 1);
        $this->saveItems();
    }

    public function saveItems()
    {
        $this->page->{$this->section} = $this->items;
        $this->page->save();
        session()->flash('success', __('تم تحديث محتوى القسم!'));
    }

    public function render()
    {
        return view('livewire.facility-page-section-content-manager');
    }
}
