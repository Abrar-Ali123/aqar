<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\DispatchesUniversalEvent;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasTranslations, DispatchesUniversalEvent, LogsActivity;

    protected $fillable = [
        'type',
        'key',
        'validation_rules',
        'is_required',
        'is_filterable',
        'has_inventory',
        'inventory_settings',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'has_inventory' => 'boolean',
        'inventory_settings' => 'array',
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    // تحديد ما إذا كان السمة تدعم إدارة المخزون
    public function supportsInventory(): bool
    {
        return $this->has_inventory;
    }

    // الحصول على إعدادات المخزون للسمة
    public function getInventorySettings(): array
    {
        return $this->inventory_settings ?? [
            'track_quantity' => true,
            'low_stock_threshold' => null,
            'expiry_tracking' => false,
            'batch_tracking' => false
        ];
    }

    // التحقق من نوع تتبع المخزون
    public function hasInventoryFeature(string $feature): bool
    {
        return ($this->getInventorySettings()[$feature] ?? false) === true;
    }
}
