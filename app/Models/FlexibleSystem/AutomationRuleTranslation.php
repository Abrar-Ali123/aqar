<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationRuleTranslation extends Model
{
    protected $fillable = [
        'automation_rule_id',
        'locale',
        'name',
        'description',
        'success_message',
        'error_message'
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }
}
