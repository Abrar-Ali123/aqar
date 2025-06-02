<?php

namespace App\Livewire;

use App\Models\FacilityPage;
use App\Models\PageTemplate;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class PageBuilder extends Component
{
    public $page;
    public $template;
    public $components = [];
    public $layout = [];
    public $styles = [];
    public $activeComponent = null;
    public $componentSettings = [];
    
    protected $listeners = [
        'componentAdded' => 'handleComponentAdded',
        'componentMoved' => 'handleComponentMoved',
        'componentRemoved' => 'handleComponentRemoved',
        'componentSettingsUpdated' => 'handleComponentSettingsUpdated',
        'layoutUpdated' => 'handleLayoutUpdated',
        'styleUpdated' => 'handleStyleUpdated'
    ];

    public function mount(FacilityPage $page)
    {
        $this->page = $page;
        $this->template = $page->template;
        $this->components = $this->template->getAvailableComponents()->toArray();
        $this->layout = $this->page->layout ?? [];
        $this->styles = $this->page->styles ?? [];
    }

    public function handleComponentAdded($componentData)
    {
        // إضافة مكون جديد إلى التخطيط
        $this->layout[] = [
            'id' => uniqid('component_'),
            'type' => $componentData['type'],
            'content' => $componentData['default_content'] ?? [],
            'settings' => $componentData['settings'] ?? [],
            'styles' => []
        ];
        
        $this->savePage();
    }

    public function handleComponentMoved($oldIndex, $newIndex)
    {
        // تحريك المكون في التخطيط
        $component = array_splice($this->layout, $oldIndex, 1)[0];
        array_splice($this->layout, $newIndex, 0, [$component]);
        
        $this->savePage();
    }

    public function handleComponentRemoved($componentId)
    {
        // حذف المكون من التخطيط
        $this->layout = array_filter($this->layout, function($component) use ($componentId) {
            return $component['id'] !== $componentId;
        });
        
        $this->savePage();
    }

    public function handleComponentSettingsUpdated($componentId, $settings)
    {
        // تحديث إعدادات المكون
        foreach ($this->layout as &$component) {
            if ($component['id'] === $componentId) {
                $component['settings'] = array_merge($component['settings'], $settings);
                break;
            }
        }
        
        $this->savePage();
    }

    public function handleLayoutUpdated($newLayout)
    {
        $this->layout = $newLayout;
        $this->savePage();
    }

    public function handleStyleUpdated($componentId, $styles)
    {
        // تحديث أنماط المكون
        foreach ($this->layout as &$component) {
            if ($component['id'] === $componentId) {
                $component['styles'] = array_merge($component['styles'] ?? [], $styles);
                break;
            }
        }
        
        $this->savePage();
    }

    protected function savePage()
    {
        $this->page->update([
            'layout' => $this->layout,
            'styles' => $this->styles
        ]);

        // مسح الكاش للصفحة
        Cache::forget($this->page->getCacheKey());
    }

    public function render()
    {
        return view('livewire.page-builder', [
            'availableComponents' => $this->components
        ]);
    }
}
