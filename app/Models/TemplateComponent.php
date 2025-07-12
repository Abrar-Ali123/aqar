<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TemplateComponent extends Model
{
    protected $fillable = [
        'name',
        'type',
        'settings',
        'styles',
        'order',
        'section_id'
    ];

    protected $casts = [
        'settings' => 'array',
        'styles' => 'array'
    ];

    



    /**
     * Get the section that owns the component.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(TemplateSection::class, 'section_id');
    }
}
