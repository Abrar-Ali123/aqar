<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ProductType extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['label'];
    protected $fillable = ['key'];
}
