<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FacilitiesList extends Component
{
    public $facilities;

    public function __construct($facilities = [])
    {
        $this->facilities = $facilities;
    }

    public function render()
    {
        return view('components.facilities-list');
    }
}
