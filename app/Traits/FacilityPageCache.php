<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait FacilityPageCache
{
    /**
     * مفتاح التخزين المؤقت للصفحة
     */
    protected function getCacheKey(): string
    {
        return 'facility_page_' . $this->id . '_' . app()->getLocale();
    }

    /**
     * مفتاح التخزين المؤقت للقسم
     */
    protected function getSectionCacheKey(string $section): string
    {
        return 'facility_section_' . $this->id . '_' . $section . '_' . app()->getLocale();
    }

    /**
     * حفظ محتوى الصفحة في التخزين المؤقت
     */
    public function cachePageContent(array $content, int $minutes = 60): void
    {
        Cache::put($this->getCacheKey(), $content, now()->addMinutes($minutes));
    }

    /**
     * حفظ محتوى قسم في التخزين المؤقت
     */
    public function cacheSectionContent(string $section, $content, int $minutes = 60): void
    {
        Cache::put($this->getSectionCacheKey($section), $content, now()->addMinutes($minutes));
    }

    /**
     * جلب محتوى الصفحة من التخزين المؤقت
     */
    public function getCachedPageContent()
    {
        return Cache::get($this->getCacheKey());
    }

    /**
     * جلب محتوى قسم من التخزين المؤقت
     */
    public function getCachedSectionContent(string $section)
    {
        return Cache::get($this->getSectionCacheKey($section));
    }

    /**
     * مسح التخزين المؤقت للصفحة
     */
    public function clearPageCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    /**
     * مسح التخزين المؤقت لقسم
     */
    public function clearSectionCache(string $section): void
    {
        Cache::forget($this->getSectionCacheKey($section));
    }

    /**
     * مسح كل التخزين المؤقت المتعلق بالصفحة
     */
    public function clearAllCache(): void
    {
        $this->clearPageCache();
        $content = json_decode($this->content, true);
        if (isset($content['sections']) && is_array($content['sections'])) {
            foreach ($content['sections'] as $section => $data) {
                $this->clearSectionCache($section);
            }
        }
    }
}
