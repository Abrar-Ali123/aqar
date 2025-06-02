<?php
namespace App\Http\Livewire;

use Livewire\Component;

class FacilityPageVersionManager extends Component
{
    public $page;
    public $versions = [];
    public $selectedVersion = null;

    public function mount($page)
    {
        $this->page = $page;
        $this->versions = $page->versions ?? [];
    }

    public function restoreVersion($i)
    {
        $version = $this->versions[$i] ?? null;
        if ($version) {
            $this->page->fill($version['data']);
            $this->page->save();
            session()->flash('success', __('تم استعادة النسخة بنجاح!'));
        }
    }

    public function render()
    {
        return view('livewire.facility-page-version-manager');
    }
}
