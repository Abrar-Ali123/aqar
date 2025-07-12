<?php

namespace App\View\Components\Facilities\Pages\Components;

use Illuminate\View\Component;

class Hero extends Component
{
    public array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function render()
    {
        return view('components.facilities.pages.components.hero');
    }
}
