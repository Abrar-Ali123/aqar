<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $table = 'product_feature';
    protected $fillable = [
        'feature_id',
        'product_id',
        'building_id',
        'land_id',
    ];

    /**
     * Get the product that owns the feature.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the feature that owns the product feature.
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
