<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadImageComponent extends Component
{
    use WithFileUploads;

    public $image;

    public function save()
    {
        if (! $this->image) {
            session()->flash('error', 'لم يتم اختيار صورة.');

            return;
        }

        $path = $this->image->store('images', 'public');
        session()->flash('message', 'تم رفع الصورة بنجاح إلى المسار: '.$path);
    }

    public function render()
    {
        return view('livewire.upload-image-component');
    }
}
