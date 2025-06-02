<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Language;

class LanguageSwitcher extends Component
{
    public $languages;
    public $currentLocale;

    public function __construct()
    {
        $this->languages = Language::where('is_active', true)->get();
        $this->currentLocale = app()->getLocale();
    }

    public function render()
    {
        return view('components.language-switcher');
    }
}
