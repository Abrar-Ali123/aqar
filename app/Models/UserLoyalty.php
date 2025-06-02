<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserLoyalty extends Model
{
    protected $fillable = [
        'user_id',
        'loyalty_tier_id',
        'total_points',
        'available_points'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loyaltyTier(): BelongsTo
    {
        return $this->belongsTo(LoyaltyTier::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function addPoints(int $points, string $type, string $description, $transactionable = null)
    {
        $multiplier = $this->loyaltyTier->points_multiplier ?? 1;
        $pointsToAdd = (int) ($points * $multiplier);

        $this->total_points += $pointsToAdd;
        $this->available_points += $pointsToAdd;
        $this->save();

        // إنشاء معاملة نقاط جديدة
        $transaction = new PointTransaction([
            'user_id' => $this->user_id,
            'points' => $pointsToAdd,
            'type' => $type,
            'description' => $description
        ]);

        if ($transactionable) {
            $transaction->transactionable()->associate($transactionable);
        }

        $transaction->save();

        // التحقق من الترقية المحتملة
        $this->checkForTierUpgrade();

        return $pointsToAdd;
    }

    public function usePoints(int $points, string $description, $transactionable = null): bool
    {
        if ($this->available_points < $points) {
            return false;
        }

        $this->available_points -= $points;
        $this->save();

        $transaction = new PointTransaction([
            'user_id' => $this->user_id,
            'points' => -$points,
            'type' => 'redeemed',
            'description' => $description
        ]);

        if ($transactionable) {
            $transaction->transactionable()->associate($transactionable);
        }

        $transaction->save();

        return true;
    }

    protected function checkForTierUpgrade()
    {
        $newTier = LoyaltyTier::getTierByPoints($this->total_points);
        
        if ($newTier && $newTier->id !== $this->loyalty_tier_id) {
            $this->loyalty_tier_id = $newTier->id;
            $this->save();
        }
    }
}
