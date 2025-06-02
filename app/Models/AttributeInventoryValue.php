<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\ProductLowStockEvent;
use App\Models\Unit;

class AttributeInventoryValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value',
        'location_id',
        'quantity',
        'unit_id',
        'low_stock_threshold',
        'expiry_date',
        'batch_number',
        'metadata'
    ];

    protected $casts = [
        'quantity' => 'decimal:5',
        'low_stock_threshold' => 'integer',
        'expiry_date' => 'date',
        'metadata' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // التحقق من توفر المخزون
    public function hasStock(): bool
    {
        return $this->quantity > 0;
    }

    // التحقق من المخزون المنخفض
    public function hasLowStock(): bool
    {
        if (!$this->low_stock_threshold) {
            return false;
        }
        return $this->quantity <= $this->low_stock_threshold;
    }

    // تحديث المخزون
    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->save();

        if ($this->hasLowStock()) {
            event(new ProductLowStockEvent($this->product, $this));
        }
    }

    // تحويل الكمية إلى وحدة أخرى
    public function getQuantityIn(Unit $unit): float
    {
        if (!$this->unit_id) {
            throw new \Exception('Current inventory value has no unit specified');
        }

        return $this->unit->convert($this->quantity, $unit);
    }

    // تحديث الكمية مع وحدة مختلفة
    public function updateQuantityWithUnit(float $quantity, Unit $unit)
    {
        if ($this->unit_id && $this->unit_id !== $unit->id) {
            // تحويل الكمية الجديدة إلى وحدة المخزون الحالية
            $quantity = $unit->convert($quantity, $this->unit);
        } else {
            $this->unit_id = $unit->id;
        }

        $this->quantity = $quantity;
        $this->save();
    }

    // التحقق من الكمية المتوفرة بوحدة معينة
    public function hasAvailableQuantity(float $quantity, Unit $unit): bool
    {
        if (!$this->unit_id) {
            return $this->quantity >= $quantity;
        }

        $convertedQuantity = $unit->convert($quantity, $this->unit);
        return $this->quantity >= $convertedQuantity;
    }

    // التحقق من انتهاء الصلاحية
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // التحقق من قرب انتهاء الصلاحية
    public function isNearExpiry(int $daysThreshold = 30): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= $daysThreshold;
    }
}
