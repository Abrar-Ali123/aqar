<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'icon',
        'type',
        'order',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'translations' => 'array'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_features')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getIconUrlAttribute()
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }
}
