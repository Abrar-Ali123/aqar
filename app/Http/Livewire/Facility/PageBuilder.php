<?php

namespace App\Http\Livewire\Facility;

use Livewire\Component;
use App\Models\FacilityPage;
use App\Models\PageTemplate;
use App\Models\Facility;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class PageBuilder extends Component
{
    use WithFileUploads;

    public $facility;
    public $template;
    public $editingSection = null;
    public $viewMode = 'desktop';
    public $searchComponent = '';
    
    public $showMediaLibrary = false;
    public $mediaSection;
    public $mediaKey;
    
    protected $listeners = [
        'sectionAdded' => 'handleSectionAdded',
        'sectionRemoved' => 'handleSectionRemoved',
        'mediaSelected' => 'handleMediaSelected'
    ];

    public function mount($facilityId = null, $templateId = null)
    {
        if ($templateId) {
            $this->template = PageTemplate::findOrFail($templateId);
        } else {
            // إنشاء قالب جديد
            $this->template = new PageTemplate([
                'name' => 'قالب جديد',
                'sections' => [],
                'is_active' => true
            ]);
        }

        if ($facilityId) {
            $this->facility = Facility::findOrFail($facilityId);
        }
    }

    public function addSection($type, $position = null)
    {
        $section = [
            'type' => $type,
            'settings' => [],
            'content' => []
        ];

        if ($position === null) {
            $this->template->sections[] = $section;
        } else {
            array_splice($this->template->sections, $position, 0, [$section]);
        }

        $this->emit('sectionAdded');
    }

    public function removeSection($index)
    {
        unset($this->template->sections[$index]);
        $this->template->sections = array_values($this->template->sections);
        
        if ($this->editingSection === $index) {
            $this->editingSection = null;
        }

        $this->emit('sectionRemoved');
    }

    public function editSection($index)
    {
        $this->editingSection = $index;
    }

    public function updateSections($orderedSections)
    {
        $this->template->sections = collect($orderedSections)->map(function($item) {
            return $this->template->sections[$item['value']];
        })->toArray();
    }

    public function duplicateSection($index)
    {
        $section = $this->template->sections[$index];
        $this->addSection($section['type'], $index + 1);
        $this->template->sections[$index + 1]['settings'] = $section['settings'];
        $this->template->sections[$index + 1]['content'] = $section['content'];
    }

    public function saveAsTemplate()
    {
        // حفظ القالب الحالي كقالب جديد يمكن إعادة استخدامه
        $newTemplate = $this->template->replicate();
        $newTemplate->name = $this->template->name . ' - نسخة';
        $newTemplate->is_default = false;
        $newTemplate->save();

        session()->flash('message', 'تم حفظ القالب كقالب جديد بنجاح');
    }

    public function openMediaLibrary($sectionIndex, $key)
    {
        $this->mediaSection = $sectionIndex;
        $this->mediaKey = $key;
        $this->showMediaLibrary = true;
    }

    public function handleMediaSelected($path)
    {
        $this->template->sections[$this->mediaSection]['settings'][$this->mediaKey] = $path;
        $this->showMediaLibrary = false;
    }

    public function saveTemplate()
    {
        $this->validate([
            'template.name' => 'required|string|max:255',
        ]);

        $this->template->save();

        if ($this->facility) {
            // إذا كان هناك منشأة، قم بإنشاء صفحة جديدة باستخدام هذا القالب
            $page = new FacilityPage([
                'facility_id' => $this->facility->id,
                'template_id' => $this->template->id,
                'title' => $this->template->name,
                'slug' => \Str::slug($this->template->name),
                'is_active' => true
            ]);
            $page->save();

            // حفظ السجل التاريخي
            $page->histories()->create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'content' => $this->template->sections
            ]);
        }

        session()->flash('message', 'تم حفظ القالب بنجاح');
        return redirect()->route('facility.pages.index');
    }

    public function previewTemplate()
    {
        // حفظ القالب مؤقتاً في الجلسة للمعاينة
        session(['preview_template' => $this->template->toArray()]);
        return redirect()->route('facility.pages.preview');
    }

    public function render()
    {
        return view('livewire.facility.page-builder');
    }
}
