<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageTemplateManager extends Component
{
    public $page;
    public $templates = [];
    public $templateName = '';

    public function mount($page)
    {
        $this->page = $page;
        $this->templates = $page->templates ?? [];
    }

    public function saveAsTemplate()
    {
        $tpls = $this->templates;
        $tpls[] = [
            'name' => $this->templateName,
            'data' => $this->page->toArray(),
        ];
        $this->templates = $tpls;
        $this->page->templates = $tpls;
        $this->page->save();
        $this->templateName = '';
        session()->flash('success', __('تم حفظ القالب!'));
    }

    public function applyTemplate($i)
    {
        $tpl = $this->templates[$i] ?? null;
        if ($tpl) {
            $this->page->fill($tpl['data']);
            $this->page->save();
            session()->flash('success', __('تم تطبيق القالب!'));
        }
    }

    public function deleteTemplate($i)
    {
        array_splice($this->templates, $i, 1);
        $this->page->templates = $this->templates;
        $this->page->save();
        session()->flash('success', __('تم حذف القالب!'));
    }

    public function render()
    {
        return view('livewire.facility-page-template-manager');
    }
}
