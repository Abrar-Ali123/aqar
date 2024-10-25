<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    public $translatedAttributes = ['name', 'info'];

    protected $fillable = [

        'is_active',
        'logo',
        'header',
        'License',
        'latitude',
        'longitude',
        'google_maps_url',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //    public function translations()
    //    {
    //        return $this->hasMany(FacilityTranslation::class);
    //    }

    //    public function getTranslation($locale)
    //    {
    //        return $this->translations->where('locale', $locale)->first();
    //    }
}
