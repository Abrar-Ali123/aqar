<?php

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Services\LocaleService;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LocaleService::class);

        // Register OpenAI Client
        $this->app->singleton(\OpenAI\Client::class, function ($app) {
            return \OpenAI\Client::factory()
                ->withApiKey(config('services.openai.api_key'))
                ->withBaseUri('https://api.openai.com/v1/')
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تسجيل المكونات
        $this->loadViewComponentsAs('', [
            \App\View\Components\FacilitiesList::class,
            \App\View\Components\ProductList::class,
            \App\View\Components\LanguageSwitcher::class,
        ]);

        // تسجيل مترجم مخصص
        Lang::macro('__', function ($key, array $replace = [], $locale = null) {
            $locale = $locale ?: App::getLocale();
            $group = 'messages';
            
            if (str_contains($key, '.')) {
                [$group, $key] = explode('.', $key, 2);
            }
            
            // البحث في الذاكرة المؤقتة أولاً
            $cacheKey = "translation_{$locale}_{$group}_{$key}";
            
            return Cache::remember($cacheKey, 60 * 24, function () use ($key, $group, $locale) {
                // البحث في قاعدة البيانات
                $translation = Translation::where('key', $key)
                    ->where('group', $group)
                    ->where('locale', $locale)
                    ->first();

                if ($translation) {
                    return $translation->text;
                }

                // إذا لم يتم العثور على الترجمة، نرجع إلى الملفات
                return trans("{$group}.{$key}", [], $locale);
            });
        });

        // تجاوز دالة الترجمة الافتراضية
        $this->app->singleton('translation.loader', function ($app) {
            return new \App\Services\CustomTranslationLoader($app['files'], $app['path.lang']);
        });

        // تعيين اللغة عند بدء التطبيق
        $locale = Request::segment(1);
        
        if ($locale) {
            $localeService = app(LocaleService::class);
            if (!$localeService->isExcludedPath($locale)) {
                $localeService->setLocale($locale);
            }
        }
    }
}
