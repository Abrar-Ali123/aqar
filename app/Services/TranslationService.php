<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Language;

class TranslationService
{
    /**
     * الحصول على ترجمة
     *
     * @param string $key مفتاح الترجمة
     * @param string $locale رمز اللغة
     * @param string|null $group مجموعة الترجمة
     * @return string|null
     */
    public function getTranslation(string $key, string $locale, ?string $group = null): ?string
    {
        $cacheKey = "translation:{$locale}:{$group}:{$key}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($key, $locale, $group) {
            return DB::table('translations')
                ->where('key', $key)
                ->where('locale', $locale)
                ->when($group, function ($query) use ($group) {
                    return $query->where('group', $group);
                })
                ->value('text');
        });
    }

    /**
     * تحديث أو إنشاء ترجمة
     *
     * @param string $key المفتاح
     * @param array $translations الترجمات
     * @param string|null $group المجموعة
     * @return bool
     */
    public function updateOrCreateTranslation(string $key, array $translations, ?string $group = null): bool
    {
        try {
            DB::beginTransaction();

            foreach ($translations as $locale => $text) {
                DB::table('translations')->updateOrInsert(
                    [
                        'key' => $key,
                        'locale' => $locale,
                        'group' => $group
                    ],
                    [
                        'text' => $text,
                        'updated_at' => now()
                    ]
                );

                // حذف الكاش
                Cache::forget("translation:{$locale}:{$group}:{$key}");
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('خطأ في تحديث الترجمات: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * نسخ الترجمات من لغة إلى أخرى
     *
     * @param string $fromLocale اللغة المصدر
     * @param string $toLocale اللغة الهدف
     * @param string|null $group المجموعة
     * @return bool
     */
    public function copyTranslations(string $fromLocale, string $toLocale, ?string $group = null): bool
    {
        try {
            $translations = DB::table('translations')
                ->where('locale', $fromLocale)
                ->when($group, function ($query) use ($group) {
                    return $query->where('group', $group);
                })
                ->get();

            DB::beginTransaction();

            foreach ($translations as $translation) {
                DB::table('translations')->updateOrInsert(
                    [
                        'key' => $translation->key,
                        'locale' => $toLocale,
                        'group' => $translation->group
                    ],
                    [
                        'text' => $translation->text,
                        'updated_at' => now()
                    ]
                );

                Cache::forget("translation:{$toLocale}:{$translation->group}:{$translation->key}");
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('خطأ في نسخ الترجمات: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * تصدير الترجمات
     *
     * @param string $locale اللغة
     * @param string|null $group المجموعة
     * @return array
     */
    public function exportTranslations(string $locale, ?string $group = null): array
    {
        return DB::table('translations')
            ->where('locale', $locale)
            ->when($group, function ($query) use ($group) {
                return $query->where('group', $group);
            })
            ->pluck('text', 'key')
            ->toArray();
    }

    /**
     * استيراد الترجمات
     *
     * @param string $locale اللغة
     * @param array $translations الترجمات
     * @param string|null $group المجموعة
     * @return bool
     */
    public function importTranslations(string $locale, array $translations, ?string $group = null): bool
    {
        try {
            DB::beginTransaction();

            foreach ($translations as $key => $text) {
                DB::table('translations')->updateOrInsert(
                    [
                        'key' => $key,
                        'locale' => $locale,
                        'group' => $group
                    ],
                    [
                        'text' => $text,
                        'updated_at' => now()
                    ]
                );

                Cache::forget("translation:{$locale}:{$group}:{$key}");
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('خطأ في استيراد الترجمات: ' . $e->getMessage());
            return false;
        }
    }
}
