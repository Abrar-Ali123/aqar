<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DynamicField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'field_type',
        'validation_rules',
        'ui_settings',
        'default_value',
        'is_required',
        'is_searchable',
        'is_filterable',
        'is_sortable',
        'dependencies',
        'order'
    ];

    protected $casts = [
        'validation_rules' => 'json',
        'ui_settings' => 'json',
        'default_value' => 'json',
        'dependencies' => 'json',
        'is_required' => 'boolean',
        'is_searchable' => 'boolean',
        'is_filterable' => 'boolean',
        'is_sortable' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(DynamicFieldTranslation::class);
    }
}
