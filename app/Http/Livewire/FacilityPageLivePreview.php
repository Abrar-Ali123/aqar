<?php
namespace App\Http\Livewire;

use App\Models\FacilityPage;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FacilityPageLivePreview extends Component
{
    public FacilityPage $page;
    public $previewMode = 'desktop';
    public $activeSections = [];
    public $previewData = [];

    protected $listeners = [
        'contentUpdated' => 'refreshPreview',
        'sectionToggled' => 'toggleSection',
        'styleUpdated' => 'refreshStyle'
    ];

    public function mount($page)
    {
        $this->page = $page;
    }

    public function render(): View
    {
        return view('livewire.facility-page-live-preview', [
            'previewUrl' => route('facilities.preview', [
                'facility' => $this->page->facility_id,
                'page' => $this->page->id
            ])
        ]);
    }
}
