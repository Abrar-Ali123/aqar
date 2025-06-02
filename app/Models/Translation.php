<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class Translation extends Model
{
    protected $fillable = ['key', 'group', 'text', 'locale'];

    /**
     * الحصول على ترجمة نص معين
     */
    public static function getTranslation($key, $group = 'general', $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $cacheKey = "translation_{$locale}_{$group}_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $group, $locale) {
            $translation = static::where('key', $key)
                ->where('group', $group)
                ->where('locale', $locale)
                ->first();

            if (!$translation && File::exists(resource_path("lang/{$locale}/{$group}.php"))) {
                $fileTranslations = require resource_path("lang/{$locale}/{$group}.php");
                if (isset($fileTranslations[$key])) {
                    $translation = static::create([
                        'key' => $key,
                        'group' => $group,
                        'text' => $fileTranslations[$key],
                        'locale' => $locale
                    ]);
                }
            }

            return $translation ? $translation->text : $key;
        });
    }

    /**
     * استيراد الترجمات من الملفات إلى قاعدة البيانات
     */
    public static function importFromFiles()
    {
        $langPath = resource_path('lang');
        $locales = File::directories($langPath);

        foreach ($locales as $localePath) {
            $locale = basename($localePath);
            $files = File::files($localePath);

            foreach ($files as $file) {
                $group = basename($file, '.php');
                $translations = require $file;

                foreach ($translations as $key => $text) {
                    static::updateOrCreate(
                        [
                            'key' => $key,
                            'group' => $group,
                            'locale' => $locale
                        ],
                        [
                            'text' => $text
                        ]
                    );
                }
            }
        }
    }
}
