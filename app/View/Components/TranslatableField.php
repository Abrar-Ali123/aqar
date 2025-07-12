<?php

namespace App\View\Components;

use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class TranslatableField extends Component
{
        public string $fieldId;
    public string $defaultLocale;
    public array $allAvailableLocales;
    public $availableLanguageNames;
    public array $initialLocales;
    public string $values;

    /**
     * Create a new component instance.
     */
        public function __construct(
        public string $name,
        public string $type = 'text',
        public bool $required = false,
        public ?Model $model = null,
        public string $placeholder = 'Enter value',
        public string $hint = ''
    ) {
        $this->fieldId = 'translatable_' . str_replace(['[', ']', '.'], '_', $this->name);

        // Fetch default language from DB, fallback to config.
        $defaultLanguage = Language::getDefaultLanguage();
        $this->defaultLocale = $defaultLanguage ? $defaultLanguage->code : config('app.fallback_locale', 'ar');

        // Fetch all active languages once.
        $activeLanguages = Language::active()->get();
        $this->allAvailableLocales = $activeLanguages->pluck('code')->toArray();
        $this->availableLanguageNames = $activeLanguages->pluck('name', 'code');

        // Determine which locales to show initially.
        $initialLocalesData = $this->model ? array_keys($this->model->getTranslations($this->name)) : [];
        
        // Ensure the default locale is always included.
        $this->initialLocales = array_unique(array_merge([$this->defaultLocale], $initialLocalesData));

        // Prepare values for JavaScript, ensuring it's a valid JSON string.
        $initialValues = $this->model ? $this->model->getTranslations($this->name) : [$this->defaultLocale => ''];
        $this->values = json_encode($initialValues ?: new \stdClass());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.translatable-field');
    }
}
