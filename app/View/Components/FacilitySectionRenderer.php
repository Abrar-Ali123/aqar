<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class FacilitySectionRenderer extends Component
{
    public $title;
    public $background;
    public $items;
    public $type;
    public $facility;

    public function __construct($title = null, $background = null, $items = [], $type = null, $facility = null)
    {
        $this->title = $title;
        $this->background = $background;
        $this->items = $items instanceof Collection ? $items : collect($items);
        $this->type = $type;
        $this->facility = $facility;
    }

    public function render()
    {
        return view('components.facility.section-renderer');
    }
}
