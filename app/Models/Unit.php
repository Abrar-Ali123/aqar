<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DispatchesUniversalEvent;
use App\Traits\LogsActivity;

class Unit extends Model
{
    use HasTranslations, DispatchesUniversalEvent, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_base',
        'is_active'
    ];

    public array $translatable = [
        'name',
        'symbol'
    ];

    protected $casts = [
        'is_base' => 'boolean',
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function fromConversions()
    {
        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }

    public function toConversions()
    {
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
    }

    // تحويل القيمة إلى وحدة أخرى
    public function convert(float $value, Unit $toUnit): float
    {
        if ($this->id === $toUnit->id) {
            return $value;
        }

        // البحث عن معامل التحويل المباشر
        $directConversion = $this->fromConversions()
            ->where('to_unit_id', $toUnit->id)
            ->where('is_active', true)
            ->first();

        if ($directConversion) {
            return $value * $directConversion->conversion_factor;
        }

        // البحث عن معامل التحويل العكسي
        $reverseConversion = $this->toConversions()
            ->where('from_unit_id', $toUnit->id)
            ->where('is_active', true)
            ->first();

        if ($reverseConversion) {
            return $value / $reverseConversion->conversion_factor;
        }

        // البحث عن تحويل من خلال الوحدة الأساسية
        $baseUnit = Unit::where('type', $this->type)
            ->where('is_base', true)
            ->first();

        if ($baseUnit) {
            // التحويل إلى الوحدة الأساسية أولاً
            $valueInBase = $this->convert($value, $baseUnit);
            // ثم التحويل من الوحدة الأساسية إلى الوحدة المطلوبة
            return $baseUnit->convert($valueInBase, $toUnit);
        }

        throw new \Exception("No conversion path found from {$this->code} to {$toUnit->code}");
    }

    // الحصول على جميع الوحدات المتوافقة (نفس النوع)
    public function getCompatibleUnits()
    {
        return Unit::where('type', $this->type)
            ->where('is_active', true)
            ->where('id', '!=', $this->id)
            ->get();
    }
}
