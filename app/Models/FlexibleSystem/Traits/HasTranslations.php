<?php

namespace App\Models\FlexibleSystem\Traits;

trait HasTranslations
{
    public function getTranslation(string $locale = null, bool $fallbackToDefault = true)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()->where('locale', $locale)->first();
        
        if (!$translation && $fallbackToDefault) {
            $translation = $this->translations()->where('locale', config('app.fallback_locale'))->first();
        }
        
        return $translation;
    }

    public function translate($locale = null)
    {
        return $this->getTranslation($locale);
    }

    public function translateOrNew($locale = null)
    {
        $translation = $this->getTranslation($locale, false);
        
        if (!$translation) {
            $translation = $this->translations()->make(['locale' => $locale ?: app()->getLocale()]);
        }
        
        return $translation;
    }

    public function saveTranslation(array $data, $locale = null)
    {
        $translation = $this->translateOrNew($locale);
        $translation->fill($data);
        $translation->save();
        
        return $translation;
    }
}
