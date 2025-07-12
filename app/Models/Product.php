<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Product extends Model
{
    use SoftDeletes, Translatable;

    /**
     * الأعمدة القابلة للترجمة
     */
    public $translatedAttributes = ['name', 'description'];

    /**
     * الأعمدة القابلة للملء
     */
    protected $fillable = [
        'is_active',
        'is_featured',
        'price',
        'image',
        'video',
        'image_gallery',
        'latitude',
        'longitude',
        'google_maps_url',
        'facility_id',
        'owner_user_id',
        'seller_user_id',
        'category_id',
        'type',
        'order',
    ];

    // العلاقات الأساسية
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Scope a query to only include filtered products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $locale = app()->getLocale();

        $query->select('products.*')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.locale', $locale)
            ->where('products.is_active', true);

        // Use null coalescing operator for cleaner access
        $searchTerm = $filters['search'] ?? $filters['q'] ?? null;
        $categoryId = $filters['category_id'] ?? $filters['category'] ?? null;

        $query->when($searchTerm, function ($query, $searchTerm) {
            return $query->where('product_translations.name', 'like', '%' . $searchTerm . '%');
        });

        $query->when($categoryId, function ($query, $categoryId) {
            return $query->where('products.category_id', $categoryId);
        });

        $query->when($filters['min_price'] ?? null, function ($query, $minPrice) {
            return $query->where('products.price', '>=', $minPrice);
        });

        $query->when($filters['max_price'] ?? null, function ($query, $maxPrice) {
            return $query->where('products.price', '<=', $maxPrice);
        });

        $facilityId = $filters['facility_id'] ?? $filters['facility'] ?? null;
        $query->when($facilityId, function ($query, $facilityId) {
            return $query->where('products.facility_id', $facilityId);
        });

        return $query;
    }
}
