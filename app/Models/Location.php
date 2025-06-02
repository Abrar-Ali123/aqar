<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'facility_id',
        'name',
        'type',
        'address',
        'is_active',
        'metadata'
    ];

    public array $translatable = [
        'name',
        'address'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function inventoryValues()
    {
        return $this->hasMany(AttributeInventoryValue::class);
    }

    public function incomingMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'to_location_id');
    }

    public function outgoingMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'from_location_id');
    }

    // الحصول على إجمالي المخزون لمنتج معين
    public function getProductStock(Product $product, ?Attribute $attribute = null)
    {
        $query = $this->inventoryValues()->where('product_id', $product->id);
        
        if ($attribute) {
            $query->where('attribute_id', $attribute->id);
        }

        return $query->sum('quantity');
    }

    // التحقق من توفر الكمية المطلوبة
    public function hasAvailableStock(Product $product, Attribute $attribute, string $value, int $quantity): bool
    {
        return $this->inventoryValues()
            ->where('product_id', $product->id)
            ->where('attribute_id', $attribute->id)
            ->where('attribute_value', $value)
            ->where('quantity', '>=', $quantity)
            ->exists();
    }
}
