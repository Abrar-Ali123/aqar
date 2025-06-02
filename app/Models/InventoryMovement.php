<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value',
        'movement_type',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'from_location_id',
        'to_location_id',
        'user_id',
        'reference_type',
        'reference_id',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
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

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // إنشاء حركة مخزون جديدة
    public static function recordMovement(
        Product $product,
        Attribute $attribute,
        string $attributeValue,
        string $movementType,
        int $quantity,
        ?Location $fromLocation = null,
        ?Location $toLocation = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
        array $metadata = []
    ): self {
        // حساب الكميات الجديدة
        $previousQuantity = 0;
        $newQuantity = 0;

        if ($fromLocation) {
            $inventory = $fromLocation->inventoryValues()
                ->where('product_id', $product->id)
                ->where('attribute_id', $attribute->id)
                ->where('attribute_value', $attributeValue)
                ->first();

            if ($inventory) {
                $previousQuantity = $inventory->quantity;
                $newQuantity = $previousQuantity - $quantity;
                $inventory->update(['quantity' => $newQuantity]);
            }
        }

        if ($toLocation) {
            $inventory = $toLocation->inventoryValues()
                ->firstOrCreate([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'attribute_value' => $attributeValue
                ], ['quantity' => 0]);

            $previousQuantity = $inventory->quantity;
            $newQuantity = $previousQuantity + $quantity;
            $inventory->update(['quantity' => $newQuantity]);
        }

        // تسجيل الحركة
        return self::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'attribute_value' => $attributeValue,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'from_location_id' => $fromLocation?->id,
            'to_location_id' => $toLocation?->id,
            'user_id' => auth()->id(),
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'metadata' => $metadata
        ]);
    }
}
