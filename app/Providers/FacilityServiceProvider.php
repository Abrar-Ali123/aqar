<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\ImageServiceInterface;
use App\Contracts\Services\TranslationServiceInterface;
use App\Services\ImageService;
use App\Services\TranslationService;

class FacilityServiceProvider extends ServiceProvider
{
    /**
     * تسجيل الخدمات
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(TranslationServiceInterface::class, TranslationService::class);

        // تسجيل التخزين المؤقت
        $this->app->singleton('facility.cache', function ($app) {
            return new \Illuminate\Cache\Repository(
                $app['cache']->store('redis')
            );
        });
    }

    /**
     * تهيئة الخدمات
     *
     * @return void
     */
    public function boot()
    {
        // تسجيل مستمعي الأحداث
        $this->app['events']->listen(
            'App\Events\FacilityUpdated',
            'App\Listeners\ClearFacilityCache'
        );
    }
}
