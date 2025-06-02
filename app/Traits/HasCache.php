<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

trait HasCache
{
    /**
     * الحصول على مفتاح الكاش للنموذج
     */
    public function getCacheKey(string $key = null): string
    {
        $modelName = strtolower(class_basename($this));
        return $key ? "{$modelName}.{$this->id}.{$key}" : "{$modelName}.{$this->id}";
    }

    /**
     * تخزين قيمة في الكاش
     */
    public function cacheSet(string $key, $value, int $ttl = 3600): void
    {
        Cache::put($this->getCacheKey($key), $value, $ttl);
    }

    /**
     * الحصول على قيمة من الكاش
     */
    public function cacheGet(string $key, $default = null)
    {
        if ($default instanceof \Closure) {
            return Cache::remember($this->getCacheKey($key), 3600, $default);
        }
        return Cache::get($this->getCacheKey($key), $default);
    }

    /**
     * حذف قيمة من الكاش
     */
    public function cacheForget(string $key): void
    {
        Cache::forget($this->getCacheKey($key));
    }

    /**
     * مسح كل الكاش المتعلق بالنموذج
     */
    public function cacheFlush(): void
    {
        $modelName = strtolower(class_basename($this));
        $pattern = "{$modelName}.{$this->id}.*";
        
        // Get all cache keys matching the pattern
        $keys = Cache::get($pattern);
        if ($keys) {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }

    /**
     * تخزين النموذج في الكاش
     */
    public function cacheModel(int $ttl = 3600): void
    {
        $this->cacheSet('model', $this, $ttl);
    }

    /**
     * الحصول على النموذج من الكاش
     */
    public static function getCachedModel(int $id)
    {
        $instance = new static;
        return Cache::get($instance->getCacheKey('model'));
    }

    /**
     * تحديث الكاش عند حفظ النموذج
     */
    public static function bootHasCache()
    {
        static::saved(function (Model $model) {
            $model->cacheModel();
        });

        static::deleted(function (Model $model) {
            $model->cacheFlush();
        });
    }
}
