<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicFieldTranslation extends Model
{
    protected $fillable = [
        'dynamic_field_id',
        'locale',
        'name',
        'description',
        'placeholder',
        'help_text'
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(DynamicField::class, 'dynamic_field_id');
    }
}
