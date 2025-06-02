<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageTranslationCenter extends Component
{
    public $page;
    public $fields = [
        ['name'=>'title','label'=>'عنوان الصفحة'],
        ['name'=>'description','label'=>'وصف الصفحة'],
        ['name'=>'seo_title','label'=>'عنوان السيو'],
        ['name'=>'seo_description','label'=>'وصف السيو'],
    ];
    public $translations = [];

    public function mount($page)
    {
        $this->page = $page;
        foreach($this->fields as $f) {
            $this->translations[$f['name']] = $page->translations[$f['name']] ?? ['ar'=>'','en'=>''];
        }
    }

    public function saveAllTranslations()
    {
        $all = $this->page->translations ?? [];
        foreach($this->fields as $f) {
            $all[$f['name']] = $this->translations[$f['name']];
        }
        $this->page->translations = $all;
        $this->page->save();
        session()->flash('success', __('تم حفظ جميع الترجمات!'));
    }

    public function render()
    {
        return view('livewire.facility-page-translation-center');
    }
}
