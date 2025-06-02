<?php

namespace App\Contracts\Services;

interface TranslationServiceInterface
{
    /**
     * الحصول على ترجمة
     *
     * @param string $key
     * @param string $locale
     * @param string|null $group
     * @return string|null
     */
    public function getTranslation(string $key, string $locale, ?string $group = null): ?string;

    /**
     * تحديث أو إنشاء ترجمة
     *
     * @param string $key
     * @param array $translations
     * @param string|null $group
     * @return bool
     */
    public function updateOrCreateTranslation(string $key, array $translations, ?string $group = null): bool;

    /**
     * نسخ الترجمات من لغة إلى أخرى
     *
     * @param string $fromLocale
     * @param string $toLocale
     * @param string|null $group
     * @return bool
     */
    public function copyTranslations(string $fromLocale, string $toLocale, ?string $group = null): bool;

    /**
     * تصدير الترجمات
     *
     * @param string $locale
     * @param string|null $group
     * @return array
     */
    public function exportTranslations(string $locale, ?string $group = null): array;

    /**
     * استيراد الترجمات
     *
     * @param string $locale
     * @param array $translations
     * @param string|null $group
     * @return bool
     */
    public function importTranslations(string $locale, array $translations, ?string $group = null): bool;
}
