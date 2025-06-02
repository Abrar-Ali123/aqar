<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'starts_at',
        'expires_at',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_uses' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeNotStarted($query)
    {
        return $query->where('starts_at', '>', now());
    }

    public function isValid()
    {
        return $this->is_active &&
            $this->starts_at <= now() &&
            $this->expires_at >= now() &&
            (!$this->max_uses || $this->usages()->count() < $this->max_uses);
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function hasReachedMaxUses()
    {
        return $this->max_uses && $this->usages()->count() >= $this->max_uses;
    }

    public function calculateDiscount($amount)
    {
        return $this->type === 'percentage'
            ? ($amount * $this->value / 100)
            : $this->value;
    }
}
