<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'locale',
        'name',
    ];

    /**
     * Get the feature that owns the translation.
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
