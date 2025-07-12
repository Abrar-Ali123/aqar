<?php

namespace App\Livewire;

use Livewire\Component;

class PermissionsComponent extends Component
{
    public $persList = [];

    public function mount()
    {
        if (empty($this->persList)) {
            $this->persList = [];
        }
    }

    public function render()
    {
        return view('livewire.permissions-component');
    }
}
