<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use App\Models\Language;
use App\Http\Middleware\Localize;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // تسجيل middleware الترجمة
        $this->app->singleton('localize', function ($app) {
            return new Localize();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // تحميل اللغات النشطة
            $languages = Language::where('is_active', true)->get();
            
            if ($languages->isNotEmpty()) {
                // تحديد اللغة الافتراضية
                $defaultLanguage = $languages->where('is_default', true)->first();
                
                if ($defaultLanguage) {
                    app()->setLocale($defaultLanguage->code);
                    config(['app.locale' => $defaultLanguage->code]);
                    config(['app.fallback_locale' => $defaultLanguage->code]);
                }
            }
        } catch (\Exception $e) {
            // في حالة وجود خطأ (مثل عدم وجود جدول اللغات بعد)
            // نستخدم الإعدادات الافتراضية من ملف التكوين
            report($e);
        }
    }
}
