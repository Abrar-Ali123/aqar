<?php

namespace App\View\Components\Facility;

use Illuminate\View\Component;

class TemplateWrapper extends Component
{
    public string $templateSlug;

    public function __construct(string $templateSlug)
    {
        $this->templateSlug = $templateSlug;
    }

    public function render()
    {
        return view('components.facility.template-wrapper');
    }
}
