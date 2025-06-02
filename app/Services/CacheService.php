<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CacheService
{
    /**
     * مدة التخزين المؤقت الافتراضية بالدقائق
     *
     * @var int
     */
    protected $defaultTtl = 60;

    /**
     * الحصول على عنصر من التخزين المؤقت
     *
     * @param string $key
     * @param \Closure $callback
     * @param int|null $ttl
     * @return mixed
     */
    public function remember(string $key, \Closure $callback, ?int $ttl = null)
    {
        return Cache::remember($key, $ttl ?? $this->defaultTtl * 60, $callback);
    }

    /**
     * حذف عنصر من التخزين المؤقت
     *
     * @param string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * حذف مجموعة من العناصر بناءً على نمط
     *
     * @param string $pattern
     * @return bool
     */
    public function forgetPattern(string $pattern): bool
    {
        $keys = $this->getKeys($pattern);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        return true;
    }

    /**
     * تخزين نموذج في التخزين المؤقت
     *
     * @param Model $model
     * @param string $key
     * @param int|null $ttl
     * @return mixed
     */
    public function cacheModel(Model $model, string $key, ?int $ttl = null)
    {
        return $this->remember($key, fn() => $model, $ttl);
    }

    /**
     * تخزين مجموعة في التخزين المؤقت
     *
     * @param Collection $collection
     * @param string $key
     * @param int|null $ttl
     * @return mixed
     */
    public function cacheCollection(Collection $collection, string $key, ?int $ttl = null)
    {
        return $this->remember($key, fn() => $collection, $ttl);
    }

    /**
     * الحصول على مفاتيح التخزين المؤقت بناءً على نمط
     *
     * @param string $pattern
     * @return array
     */
    protected function getKeys(string $pattern): array
    {
        $redis = Cache::getStore()->getRedis();
        return $redis->keys($pattern);
    }

    /**
     * تعيين مدة التخزين المؤقت الافتراضية
     *
     * @param int $minutes
     * @return void
     */
    public function setDefaultTtl(int $minutes): void
    {
        $this->defaultTtl = $minutes;
    }
}
