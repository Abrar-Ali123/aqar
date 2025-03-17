<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'name',
        'icon',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_feature', 'product_id', 'feature_id');
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'product_feature', 'feature_id', 'building_id');
    }

    public function lands()
    {
        return $this->belongsToMany(Land::class, 'product_feature', 'land_id', 'feature_id');
    }

    /**
     * Get the product features for the feature.
     */
    public function productFeatures()
    {
        return $this->hasMany(ProductFeature::class);
    }

    /**
     * Get the feature name based on locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->name : '';
    }
}
