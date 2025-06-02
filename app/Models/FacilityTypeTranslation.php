<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityTypeTranslation extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'custom_translations'
    ];

    protected $casts = [
        'custom_translations' => 'json'
    ];

    /**
     * Get the facility type that owns the translation
     */
    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }
}
