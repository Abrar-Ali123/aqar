<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessRuleTranslation extends Model
{
    protected $fillable = [
        'business_rule_id',
        'locale',
        'name',
        'description',
        'error_message',
        'success_message'
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(BusinessRule::class, 'business_rule_id');
    }
}
