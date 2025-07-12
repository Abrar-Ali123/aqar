<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'subscription_ends_at',
        'api_model'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'subscription_ends_at' => 'date'
    ];

    public static function isFeatureEnabled(): bool
    {
        $settings = self::first();
        
        if (!$settings) {
            return false;
        }

        return $settings->is_enabled && 
               ($settings->subscription_ends_at === null || 
                $settings->subscription_ends_at->isFuture());
    }

    public static function getApiModel(): string
    {
        return self::first()?->api_model ?? 'gpt-3.5-turbo';
    }
}
