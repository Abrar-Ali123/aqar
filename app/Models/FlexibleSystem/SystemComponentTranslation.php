<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemComponentTranslation extends Model
{
    protected $fillable = [
        'system_component_id',
        'locale',
        'name',
        'description'
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(SystemComponent::class, 'system_component_id');
    }
}
