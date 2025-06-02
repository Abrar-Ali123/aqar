<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Language;
use Illuminate\Support\Str;

class TranslatableField extends Component
{
    public $name;
    public $label;
    public $type;
    public $languages;
    public $translations;
    public $required;
    public $options;
    
    public function __construct(
        string $name,
        $languages = null,
        $translations = null,
        string $label = null,
        string $type = 'text',
        bool $required = false,
        array $options = []
    ) {
        $this->name = $name;
        $this->label = $label ?? Str::title(str_replace('_', ' ', $name));
        $this->type = $type;
        $this->languages = $languages ?? Language::active()->orderBy('order')->get();
        $this->translations = $translations;
        $this->required = $required;
        $this->options = array_merge($this->getDefaultOptions($type), $options);
    }

    public function render()
    {
        return view('components.translatable-field');
    }

    public function getFieldType()
    {
        return config("translatable.field_types.{$this->type}", config('translatable.field_types.text'));
    }

    public function getDirection($locale)
    {
        return config("translatable.defaults.direction.{$locale}", 'ltr');
    }

    public function isRequired($language)
    {
        if ($this->required) {
            return true;
        }
        
        return $language->is_required || in_array($language->code, config('translatable.defaults.required_languages', []));
    }

    public function getValue($locale, $field = null)
    {
        $field = $field ?? $this->name;
        
        if (!$this->translations) {
            return old("{$field}.{$locale}");
        }

        return old("{$field}.{$locale}", 
            $this->translations[$locale]->{$field} ?? null
        );
    }

    private function getDefaultOptions($type)
    {
        return config("translatable.field_types.{$type}", []);
    }
}
