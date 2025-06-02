<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateAnalytic extends Model
{
    protected $fillable = [
        'template_id',
        'views',
        'downloads',
        'installations',
        'bounce_rate',
        'avg_time',
        'device_type',
        'browser',
        'country',
        'referrer',
        'date'
    ];

    protected $casts = [
        'views' => 'integer',
        'downloads' => 'integer',
        'installations' => 'integer',
        'bounce_rate' => 'float',
        'avg_time' => 'integer',
        'date' => 'date'
    ];

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public static function recordView($template_id)
    {
        $analytics = self::firstOrNew([
            'template_id' => $template_id,
            'date' => now()->format('Y-m-d'),
            'device_type' => self::detectDeviceType(),
            'browser' => self::detectBrowser(),
            'country' => self::detectCountry()
        ]);

        $analytics->views = ($analytics->views ?? 0) + 1;
        $analytics->save();
    }

    public static function recordDownload($template_id)
    {
        $analytics = self::firstOrNew([
            'template_id' => $template_id,
            'date' => now()->format('Y-m-d'),
            'device_type' => self::detectDeviceType(),
            'browser' => self::detectBrowser(),
            'country' => self::detectCountry()
        ]);

        $analytics->downloads = ($analytics->downloads ?? 0) + 1;
        $analytics->save();
    }

    public static function recordInstallation($template_id)
    {
        $analytics = self::firstOrNew([
            'template_id' => $template_id,
            'date' => now()->format('Y-m-d'),
            'device_type' => self::detectDeviceType(),
            'browser' => self::detectBrowser(),
            'country' => self::detectCountry()
        ]);

        $analytics->installations = ($analytics->installations ?? 0) + 1;
        $analytics->save();
    }

    private static function detectDeviceType()
    {
        $agent = request()->userAgent();
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $agent)) {
            return 'tablet';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $agent)) {
            return 'mobile';
        }
        return 'desktop';
    }

    private static function detectBrowser()
    {
        return request()->header('User-Agent');
    }

    private static function detectCountry()
    {
        return request()->header('CF-IPCountry') ?? 'unknown';
    }
}
