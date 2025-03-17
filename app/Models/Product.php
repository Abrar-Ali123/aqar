<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'is_active',
        'price',
        'image',
        'video',
        'image_gallery',
        'latitude',
        'longitude',
        'google_maps_url',
        'facility_id',
        'property_type',
        'owner_user_id',
        'seller_user_id',
        'category_id',
    ];


    /**
     * Get the attribute values for the product.
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the features for the product.
     */
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the facility that owns the product.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the owner user.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Get the seller user.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    /**
     * Get the product name based on locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->name : '';
    }

    /**
     * Get the product description based on locale.
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->description : '';
    }

    protected function setPropertyTypeAttribute($value)
    {
        $map = [
            'apartment' => 'apt',
            'villa' => 'vil',
            'land' => 'land',
            'commercial' => 'com'
        ];

        $this->attributes['property_type'] = $map[$value] ?? $value;
    }

    public function getPropertyTypeTextAttribute()
    {
        $map = [
            'apt' => 'apartment',
            'vil' => 'villa',
            'land' => 'land',
            'com' => 'commercial'
        ];

        return $map[$this->property_type] ?? $this->property_type;
    }
}
