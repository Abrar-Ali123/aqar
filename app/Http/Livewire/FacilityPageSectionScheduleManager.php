<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageSectionScheduleManager extends Component
{
    public $page;
    public $section;
    public $schedule = [
        'start' => '',
        'end' => '',
    ];

    public function mount($page, $section)
    {
        $this->page = $page;
        $this->section = $section;
        $this->schedule = $page->section_schedules[$section] ?? $this->schedule;
    }

    public function saveSchedule()
    {
        $all = $this->page->section_schedules ?? [];
        $all[$this->section] = $this->schedule;
        $this->page->section_schedules = $all;
        $this->page->save();
        session()->flash('success', __('تم حفظ الجدولة!'));
    }

    public function render()
    {
        return view('livewire.facility-page-section-schedule-manager');
    }
}
