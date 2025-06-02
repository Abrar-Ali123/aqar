<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'trigger_event',
        'conditions',
        'actions',
        'schedule',
        'retry_policy',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'conditions' => 'json',
        'actions' => 'json',
        'schedule' => 'json',
        'retry_policy' => 'json',
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(AutomationRuleTranslation::class);
    }
}
