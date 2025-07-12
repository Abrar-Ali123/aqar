<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSection extends Model
{
    protected $fillable = [
        'name',
        'template_id',
        'order',
        'styles',
        'settings',
    ];

    protected $casts = [
        'styles' => 'array',
        'settings' => 'array',
    ];

    /**
     * Get the template that owns the section.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    /**
     * Get the components in this section.
     */
    public function components(): HasMany
    {
        return $this->hasMany(TemplateComponent::class, 'section_id');
    }
}
