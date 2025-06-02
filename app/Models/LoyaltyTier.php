<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyTier extends Model
{
    protected $fillable = [
        'name',
        'required_points',
        'points_multiplier',
        'benefits'
    ];

    protected $casts = [
        'required_points' => 'integer',
        'points_multiplier' => 'float',
        'benefits' => 'array'
    ];

    public function userLoyalty(): HasMany
    {
        return $this->hasMany(UserLoyalty::class);
    }

    public static function getTierByPoints(int $points)
    {
        return static::where('required_points', '<=', $points)
            ->orderBy('required_points', 'desc')
            ->first();
    }
}
