<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UiTemplateTranslation extends Model
{
    protected $fillable = [
        'ui_template_id',
        'locale',
        'name',
        'description'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(UiTemplate::class, 'ui_template_id');
    }
}
