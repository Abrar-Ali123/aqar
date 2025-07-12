<?php

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Services\LocaleService;
use App\Services\FacilityPageService;
use Illuminate\Support\Facades\Request;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LocaleService::class);
        $this->app->singleton(FacilityPageService::class);

        // Register OpenAI Client
        $this->app->singleton(\OpenAI\Client::class, function ($app) {
            $apiKey = config('services.openai.api_key');
            if (empty($apiKey)) {
                throw new \RuntimeException('OpenAI API key is not set. Please add OPENAI_API_KEY to your .env file.');
            }
            
            return \OpenAI::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client([
                    'verify' => false,  // تجاوز التحقق من شهادة SSL
                    'timeout' => 30
                ]))
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);



 
        // تسجيل المكونات
        $this->loadViewComponentsAs('', [
            \App\View\Components\FacilitiesList::class,
            \App\View\Components\ProductList::class,
            \App\View\Components\LanguageSwitcher::class,
            \App\View\Components\ApplicationLogo::class, // شعار التطبيق
            \App\View\Components\AppLayout::class
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
