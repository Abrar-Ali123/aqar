<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleService
{
    /**
     * المسارات المستثناة
     */
    protected array $excludedPaths = [
        '_debugbar',
        'api',
        'webhooks',
    ];

    /**
     * تعيين لغة التطبيق
     */
    public function setLocale(?string $locale = null): void
    {
        try {
            // إذا لم يتم تحديد لغة، استخدم اللغة الافتراضية
            if (!$locale) {
                $this->setDefaultLocale();
                return;
            }

            // تحقق من أن اللغة موجودة وفعالة
            $language = Language::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if ($language) {
                $this->setApplicationLocale($language);
            } else {
                $this->setDefaultLocale();
            }
        } catch (\Exception $e) {
            report($e);
            $this->setFallbackLocale();
        }
    }

    /**
     * تعيين اللغة الافتراضية
     */
    public function setDefaultLocale(): void
    {
        try {
            $language = Language::getDefaultLanguage();
            if ($language) {
                $this->setApplicationLocale($language);
            } else {
                $this->setFallbackLocale();
            }
        } catch (\Exception $e) {
            report($e);
            $this->setFallbackLocale();
        }
    }

    /**
     * تعيين لغة الموقع والاتجاه
     */
    protected function setApplicationLocale(Language $language): void
    {
        App::setLocale($language->code);
        Session::put('locale', $language->code);
        Session::put('direction', $language->direction);
    }

    /**
     * تعيين اللغة الاحتياطية
     */
    protected function setFallbackLocale(): void
    {
        App::setLocale('en');
        Session::put('locale', 'en');
        Session::put('direction', 'ltr');
    }

    /**
     * التحقق من المسارات المستثناة
     */
    public function isExcludedPath(string $path): bool
    {
        return in_array($path, $this->excludedPaths);
    }
}
