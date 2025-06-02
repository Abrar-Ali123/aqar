<?php

namespace App\Traits;

use App\Models\Language;

trait HasTranslations
{
    /**
     * الحصول على الترجمة بلغة محددة
     */
    public function getTranslation($attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        return $this->translations()
            ->where('locale', $locale)
            ->value($attribute);
    }

    /**
     * الحصول على الترجمة بلغة محددة
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        return $this->translations()
            ->where('locale', $locale)
            ->first();
    }

    /**
     * الحصول على قيمة الخاصية
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatedAttributes ?? [])) {
            $value = $this->getTranslation($key);
            return $value !== null ? $value : parent::getAttribute($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * الحصول على كل الترجمات المتوفرة لخاصية معينة
     */
    public function getTranslationsFor($attribute)
    {
        return $this->translations()
            ->pluck($attribute, 'locale')
            ->toArray();
    }

    /**
     * تحديث أو إنشاء ترجمة
     */
    public function setTranslation($attribute, $locale, $value)
    {
        $this->translations()->updateOrCreate(
            ['locale' => $locale],
            [$attribute => $value]
        );
    }

    /**
     * تحديث ترجمات متعددة دفعة واحدة
     */
    public function setTranslations(array $translations)
    {
        foreach ($translations as $locale => $attributes) {
            foreach ($attributes as $attribute => $value) {
                $this->setTranslation($attribute, $locale, $value);
            }
        }
    }

    /**
     * التحقق من وجود ترجمة
     */
    public function hasTranslation($attribute, $locale = null)
    {
        return (bool) $this->getTranslation($attribute, $locale);
    }

    /**
     * الحصول على كل الترجمات المتوفرة
     */
    public function getTranslationsArray()
    {
        $translations = [];
        
        foreach ($this->translations as $translation) {
            $translations[$translation->locale] = $translation->getAttributes();
        }

        return $translations;
    }

    /**
     * التحقق من اكتمال الترجمات الإلزامية
     */
    public function hasRequiredTranslations()
    {
        $requiredLocales = config('app.required_locales', ['ar']);
        $requiredAttributes = $this->getTranslatableAttributes();

        foreach ($requiredLocales as $locale) {
            foreach ($requiredAttributes as $attribute) {
                if (!$this->hasTranslation($attribute, $locale)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * الحصول على الحقول القابلة للترجمة
     */
    public function getTranslatableAttributes()
    {
        return $this->translatedAttributes ?? [];
    }
}
