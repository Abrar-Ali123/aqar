<?php

namespace App\View\Components\Facility;

use Illuminate\View\Component;

class ThemeStyles extends Component
{
    public array $themeConfig;

    public function __construct(array $themeConfig)
    {
        $this->themeConfig = $themeConfig;
    }

    public function render()
    {
        return view('components.facility.theme-styles');
    }
}
