<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UiTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'layout',
        'components',
        'styles',
        'behaviors',
        'responsive_settings',
        'is_active',
        'order'
    ];

    protected $casts = [
        'layout' => 'json',
        'components' => 'json',
        'styles' => 'json',
        'behaviors' => 'json',
        'responsive_settings' => 'json',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(UiTemplateTranslation::class);
    }
}
