<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class BusinessSector extends Model implements TranslatableContract
{    
    use Translatable;

    public array $translatedAttributes = ['name', 'description'];
    protected $fillable = [
        'icon',
        'is_active',
        'order'
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
