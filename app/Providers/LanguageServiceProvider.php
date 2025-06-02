<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Language;

class LanguageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // تعيين اللغة الافتراضية
        $this->setDefaultLocale();

        // تكوين URL للغات
        $this->configureLocaleUrls();
    }

    protected function setDefaultLocale(): void
    {
        try {
            $locale = request()->segment(1);

            // إذا كان المسار من المسارات المستثناة
            if ($this->isExcludedPath($locale)) {
                return;
            }

            // البحث عن اللغة في قاعدة البيانات
            $language = null;
            if ($locale) {
                $language = Language::where('code', $locale)
                    ->where('is_active', true)
                    ->first();
            }

            // إذا لم تكن اللغة موجودة، استخدم اللغة الافتراضية
            if (!$language) {
                $language = Language::where('is_default', true)
                    ->where('is_active', true)
                    ->first();
            }

            // تعيين اللغة
            if ($language) {
                app()->setLocale($language->code);
                session(['locale' => $language->code, 'direction' => $language->direction]);
            }
        } catch (\Exception $e) {
            report($e);
            // استخدام اللغة الاحتياطية
            app()->setLocale(config('app.fallback_locale', 'en'));
        }
    }

    protected function configureLocaleUrls(): void
    {
        // إضافة اللغة للروابط
        URL::defaults(['locale' => app()->getLocale()]);

        // تأكد من أن كل الروابط تحتوي على بريفكس اللغة
        URL::macro('localizedRoute', function (string $name, $parameters = [], $absolute = true) {
            $locale = app()->getLocale();
            return URL::route($name, array_merge(['locale' => $locale], $parameters), $absolute);
        });
    }

    protected function isExcludedPath(?string $path): bool
    {
        return in_array($path, [
            '_debugbar',
            'api',
            'webhooks',
            'telescope',
            'horizon',
        ]);
    }
}
