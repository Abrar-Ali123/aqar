<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\FacilityPage;

class FacilityPagesStatistics extends Component
{
    public $facilityId;
    public $pages = [];
    public $stats = [];

    public function mount($facilityId)
    {
        $this->facilityId = $facilityId;
        $this->pages = FacilityPage::where('facility_id', $facilityId)->withCount('visits')->get();
        $this->stats = [
            'total' => $this->pages->count(),
            'active' => $this->pages->where('is_active', true)->count(),
            'with_reviews' => $this->pages->where('enable_reviews', true)->count(),
            'total_visits' => $this->pages->sum('visits_count'),
            'most_visited' => $this->pages->sortByDesc('visits_count')->take(3),
        ];
    }

    public function render()
    {
        return view('livewire.facility-pages-statistics');
    }
}
