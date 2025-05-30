<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'attribute_id',
        'locale',
        'name',
        'symbol',
    ];

    /**
     * Get the attribute that owns the translation.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
