<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageSectionManager extends Component
{
    public $page;
    public $availableSections = [
        'gallery', 'team', 'services', 'faq', 'offers', 'announcements', 'partners', 'social', 'testimonials', 'blog', 'enable_booking', 'map'
    ];
    public $enabledSections = [];

    public function mount($page)
    {
        $this->page = $page;
        $this->enabledSections = array_filter($this->availableSections, fn($s) => !empty($page->$s) || ($s === 'enable_booking' && $page->enable_booking) || ($s === 'map' && $page->facility->latitude && $page->facility->longitude));
    }

    public function saveSections()
    {
        foreach ($this->availableSections as $section) {
            if (in_array($section, $this->enabledSections)) {
                if ($section === 'enable_booking') $this->page->enable_booking = true;
                // يمكن تفعيل القسم أو تعيين بيانات افتراضية إذا لزم
            } else {
                if ($section === 'enable_booking') $this->page->enable_booking = false;
                // يمكن تعطيل القسم أو مسح بياناته إذا لزم
            }
        }
        $this->page->save();
        session()->flash('success', __('تم تحديث الأقسام بنجاح!'));
    }

    public function render()
    {
        return view('livewire.facility-page-section-manager');
    }
}
