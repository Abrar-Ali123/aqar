<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // تعيين حد الذاكرة
        ini_set('memory_limit', config('performance.memory.limit'));

        // تعيين وقت التنفيذ
        ini_set('max_execution_time', config('performance.database.max_execution_time'));

        // تعطيل lazy loading للعلاقات
        Model::preventLazyLoading(!app()->isProduction());

        if (config('performance.cache.enable_query_cache')) {
            // تفعيل تخزين الاستعلامات
            DB::connection()->enableQueryLog();
        }

        // تحسين أداء Eloquent
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            $class = get_class($model);
            logger()->warning("Attempted to lazy load [{$relation}] on model [{$class}].");
        });

        // تنظيف الكاش القديم تلقائياً
        if (app()->isProduction()) {
            $this->scheduleCache();
        }
    }

    protected function scheduleCache()
    {
        // تنظيف الكاش كل ساعة
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);
        
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command('cache:clear')->daily();
        $schedule->command('view:clear')->daily();
    }
}
