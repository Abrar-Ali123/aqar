<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    protected $fillable = [
        'from_unit_id',
        'to_unit_id',
        'conversion_factor',
        'is_active'
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:5',
        'is_active' => 'boolean'
    ];

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    // تحويل القيمة
    public function convert(float $value): float
    {
        return $value * $this->conversion_factor;
    }

    // تحويل القيمة في الاتجاه المعاكس
    public function reverseConvert(float $value): float
    {
        return $value / $this->conversion_factor;
    }

    // إنشاء تحويل عكسي
    public function createReverse(): self
    {
        return self::create([
            'from_unit_id' => $this->to_unit_id,
            'to_unit_id' => $this->from_unit_id,
            'conversion_factor' => 1 / $this->conversion_factor,
            'is_active' => $this->is_active
        ]);
    }
}
