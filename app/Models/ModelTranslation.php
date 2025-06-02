<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelTranslation extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'locale',
        'name',
        'description',
        'meta_title',
        'meta_description',
        'slug',
        'details',
        'address',
        'notes'
    ];

    public $timestamps = false;

    public function translatable()
    {
        return $this->morphTo('model');
    }
}
