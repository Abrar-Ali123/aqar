<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\FacilityPage;
use App\Models\FacilityPageVisit;
use Carbon\Carbon;

class FacilityPagesVisitsChart extends Component
{
    public $facilityId;
    public $labels = [];
    public $data = [];

    public function mount($facilityId)
    {
        $this->facilityId = $facilityId;
        $days = collect(range(0, 6))->map(function($i) {
            return Carbon::now()->subDays(6 - $i)->format('Y-m-d');
        });
        $this->labels = $days->map(fn($d) => date('D', strtotime($d)))->toArray();
        $visits = FacilityPageVisit::whereHas('page', function($q) {
            $q->where('facility_id', $this->facilityId);
        })
        ->whereBetween('visited_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
        ->get()
        ->groupBy(fn($v) => Carbon::parse($v->visited_at)->format('Y-m-d'));
        $this->data = $days->map(fn($d) => isset($visits[$d]) ? $visits[$d]->count() : 0)->toArray();
    }

    public function render()
    {
        return view('livewire.facility-pages-visits-chart');
    }
}
