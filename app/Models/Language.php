<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{App, Session, Cache};

class Language extends Model
{
    protected $fillable = [
        'name',
        'code',
        'direction',
        'is_active',
        'is_default',
        'is_required',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_required' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get current active language
     */
    public static function getCurrentLanguage()
    {
        $locale = app()->getLocale();
        
        return Cache::remember("current_language_{$locale}", 60 * 24, function () use ($locale) {
            $language = self::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if (!$language) {
                $language = self::getDefaultLanguage();
                if ($language) {
                    app()->setLocale($language->code);
                    session(['locale' => $language->code, 'direction' => $language->direction]);
                }
            }

            return $language;
        });
    }

    /**
     * Get default language
     */
    public static function getDefaultLanguage()
    {
        return Cache::remember('default_language', 60 * 24, function () {
            return self::where('is_default', true)
                ->where('is_active', true)
                ->first() ?? self::where('is_active', true)
                ->orderBy('id')
                ->first();
        });
    }

    /**
     * Get all active languages except current
     */
    public static function getOtherLanguages()
    {
        $currentLocale = app()->getLocale();
        
        return Cache::remember("other_languages_{$currentLocale}", 60 * 24, function () use ($currentLocale) {
            return self::where('is_active', true)
                ->where('code', '!=', $currentLocale)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function (Language $language) {
            // إذا تم تعيين هذه اللغة كافتراضية، قم بإلغاء تعيين اللغات الأخرى
            if ($language->is_default) {
                self::where('id', '!=', $language->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            // مسح الكاش
            Cache::forget('default_language');
            Cache::forget('current_language_' . app()->getLocale());
            Cache::forget('other_languages_' . app()->getLocale());
        });

        static::deleted(function (Language $language) {
            // مسح الكاش عند حذف لغة
            Cache::forget('default_language');
            Cache::forget('current_language_' . $language->code);
            Cache::forget('other_languages_' . $language->code);
        });
    }

    /**
     * Check if language is RTL
     */
    public function isRtl()
    {
        return $this->direction === 'rtl';
    }
}
