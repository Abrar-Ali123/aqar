<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PriceAlert extends Model
{
    protected $fillable = [
        'user_id',
        'target_price',
        'is_active',
        'last_notified_at'
    ];

    protected $casts = [
        'target_price' => 'decimal:2',
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function alertable(): MorphTo
    {
        return $this->morphTo();
    }

    public function shouldNotify($currentPrice): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // إذا كان السعر الحالي أقل من أو يساوي السعر المستهدف
        if ($currentPrice <= $this->target_price) {
            // إذا لم يتم الإخطار من قبل أو مر أكثر من 24 ساعة على آخر إخطار
            if (!$this->last_notified_at || $this->last_notified_at->diffInHours(now()) >= 24) {
                return true;
            }
        }

        return false;
    }

    public function markNotified(): void
    {
        $this->update(['last_notified_at' => now()]);
    }
}
