<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];
}
