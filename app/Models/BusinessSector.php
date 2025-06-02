<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSector extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'icon',
        'description',
        'description_en',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function categories()
    {
        return $this->hasMany(BusinessCategory::class, 'sector_id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'sector_id');
    }

    public function modules()
    {
        return $this->morphMany(CategoryModule::class, 'categorizable');
    }
}
