<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FacilityPageMediaManager extends Component
{
    use WithFileUploads;

    public $page;
    public $media = [];
    public $mediaFile;

    public function mount($page)
    {
        $this->page = $page;
        $this->media = $page->media ?? [];
    }

    public function uploadMedia()
    {
        $path = $this->mediaFile->store('facility-media', 'public');
        $this->media[] = ['path' => $path];
        $this->page->media = $this->media;
        $this->page->save();
        $this->mediaFile = null;
        session()->flash('success', __('تم رفع الوسيط!'));
    }

    public function deleteMedia($i)
    {
        if(isset($this->media[$i])) {
            Storage::disk('public')->delete($this->media[$i]['path']);
            array_splice($this->media, $i, 1);
            $this->page->media = $this->media;
            $this->page->save();
            session()->flash('success', __('تم حذف الوسيط!'));
        }
    }

    public function render()
    {
        return view('livewire.facility-page-media-manager');
    }
}
