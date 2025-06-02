<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageSectionStyleManager extends Component
{
    public $page;
    public $section;
    public $style = [
        'background' => '#ffffff',
        'color' => '#000000',
        'bg_image' => '',
    ];

    public function mount($page, $section)
    {
        $this->page = $page;
        $this->section = $section;
        $this->style = $page->section_styles[$section] ?? $this->style;
    }

    public function saveStyle()
    {
        $styles = $this->page->section_styles ?? [];
        $styles[$this->section] = $this->style;
        $this->page->section_styles = $styles;
        $this->page->save();
        session()->flash('success', __('تم حفظ تصميم القسم!'));
    }

    public function render()
    {
        return view('livewire.facility-page-section-style-manager');
    }
}
