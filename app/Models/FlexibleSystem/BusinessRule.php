<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'conditions',
        'actions',
        'priority',
        'is_active',
        'error_handling',
        'logging_settings'
    ];

    protected $casts = [
        'conditions' => 'json',
        'actions' => 'json',
        'error_handling' => 'json',
        'logging_settings' => 'json',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(BusinessRuleTranslation::class);
    }
}
