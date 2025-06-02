<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageSectionTranslationManager extends Component
{
    public $page;
    public $section;
    public $translations = [];
    public $languages = ['ar', 'en'];

    public function mount($page, $section)
    {
        $this->page = $page;
        $this->section = $section;
        $this->translations = $page->section_translations[$section] ?? [];
    }

    public function saveTranslations()
    {
        $all = $this->page->section_translations ?? [];
        $all[$this->section] = $this->translations;
        $this->page->section_translations = $all;
        $this->page->save();
        session()->flash('success', __('تم حفظ الترجمة!'));
    }

    public function render()
    {
        return view('livewire.facility-page-section-translation-manager');
    }
}
