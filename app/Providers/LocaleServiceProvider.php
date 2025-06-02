<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if ($this->isExcludedPath()) {
                return;
            }

            $locale = Request::segment(1);
            $defaultLanguage = Language::getDefaultLanguage();

            if (!$defaultLanguage) {
                return;
            }

            // إذا كان المسار الرئيسي
            if (Request::path() === '/' || !$locale) {
                $this->setApplicationLocale($defaultLanguage);
                if (!app()->runningInConsole()) {
                    header('Location: /' . $defaultLanguage->code);
                    exit;
                }
                return;
            }

            // تحقق من أن اللغة موجودة وفعالة
            $language = Language::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if ($language) {
                $this->setApplicationLocale($language);
                return;
            }

            // إذا كان المسار يحتوي على أرقام فقط، استخدم اللغة الافتراضية
            if (is_numeric($locale)) {
                $this->setApplicationLocale($defaultLanguage);
                return;
            }

            // إذا كانت اللغة غير صالحة، استخدم اللغة الافتراضية
            $this->setApplicationLocale($defaultLanguage);

            // إعادة توجيه إلى نفس المسار ولكن مع اللغة الافتراضية
            if (!app()->runningInConsole()) {
                $segments = Request::segments();
                $segments[0] = $defaultLanguage->code;
                header('Location: /' . implode('/', $segments));
                exit;
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Set the application locale and direction
     */
    protected function setApplicationLocale(Language $language): void
    {
        app()->setLocale($language->code);
        session(['locale' => $language->code, 'direction' => $language->direction]);
    }

    /**
     * Check if the current path is excluded
     */
    protected function isExcludedPath(): bool
    {
        $excludedPaths = [
            '_debugbar',
            'api',
            'webhooks',
        ];

        $firstSegment = Request::segment(1);
        return $firstSegment && in_array($firstSegment, $excludedPaths);
    }
}
