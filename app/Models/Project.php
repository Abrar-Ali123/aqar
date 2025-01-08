<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'author', 'latitude', 'longitude', 'google_maps_url', 'facility_id', 'image', 'seller_user_id', 'project_type',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
