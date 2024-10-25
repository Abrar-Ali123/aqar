<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FacilityTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [

        'facility_id',
        'name', 'info',
        'locale',

    ];
    protected $table = 'facility_translations';

    public function Facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
