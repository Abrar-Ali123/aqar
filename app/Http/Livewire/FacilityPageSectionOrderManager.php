<?php
namespace App\Http\Livewire;

use App\Models\FacilityPage;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FacilityPageSectionOrderManager extends Component
{
    public FacilityPage $page;
    public array $sections = [];
    public array $sectionMeta = [];
    
    protected $listeners = ['sectionOrderUpdated' => 'updateOrder'];

    public function mount(FacilityPage $page)
    {
        $this->page = $page;
        $this->sections = $page->sections->toArray();
        
        // تحميل البيانات الوصفية لكل قسم
        foreach ($this->sections as $section) {
            $this->sectionMeta[$section] = [
                'title' => __('sections.' . $section),
                'icon' => $this->getSectionIcon($section),
                'status' => $this->getSectionStatus($section)
            ];
        }
    }

    public function updateOrder(array $newOrder): void
    {
        // تحديث ترتيب الأقسام
        $this->sections = $newOrder;
        $this->page->sections = collect($newOrder);
        $this->page->save();
        
        $this->emit('sectionsReordered');
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => __('تم تحديث ترتيب الأقسام بنجاح')
        ]);
    }

    protected function getSectionIcon(string $section): string
    {
        return match($section) {
            'header' => 'fa-heading',
            'about' => 'fa-info-circle',
            'services' => 'fa-concierge-bell',
            'gallery' => 'fa-images',
            'team' => 'fa-users',
            'testimonials' => 'fa-quote-right',
            'contact' => 'fa-envelope',
            'map' => 'fa-map-marker-alt',
            'blog' => 'fa-blog',
            'products' => 'fa-shopping-cart',
            default => 'fa-puzzle-piece'
        };
    }

    protected function getSectionStatus(string $section): string
    {
        // التحقق من حالة القسم (مكتمل، ناقص، غير مفعل)
        $hasContent = !empty($this->page->getContent([$section]));
        $isActive = in_array($section, $this->page->sections->toArray());
        
        if (!$isActive) return 'inactive';
        if (!$hasContent) return 'incomplete';
        return 'complete';
    }

    public function render(): View
    {
        return view('livewire.facility-page-section-order-manager', [
            'sections' => $this->sections,
            'meta' => $this->sectionMeta
        ]);
    }
}
