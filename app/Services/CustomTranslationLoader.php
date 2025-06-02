<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Translation\FileLoader;
use Illuminate\Support\Facades\Cache;

class CustomTranslationLoader extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        // إذا كان هناك namespace، نستخدم الـ FileLoader الأصلي
        if ($namespace !== null && $namespace !== '*') {
            return parent::load($locale, $group, $namespace);
        }

        // البحث في الذاكرة المؤقتة أولاً
        $cacheKey = "translations_{$locale}_{$group}";
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($locale, $group) {
            // جلب الترجمات من قاعدة البيانات
            $translations = Translation::where('locale', $locale)
                ->where('group', $group)
                ->pluck('text', 'key')
                ->toArray();

            // إذا لم نجد ترجمات في قاعدة البيانات، نرجع إلى الملفات
            if (empty($translations)) {
                return parent::load($locale, $group, null);
            }

            return $translations;
        });
    }
}
