<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemComponent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'is_core',
        'is_active',
        'settings',
        'requirements',
        'order'
    ];

    protected $casts = [
        'settings' => 'json',
        'requirements' => 'json',
        'is_core' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(SystemComponentTranslation::class);
    }
}
