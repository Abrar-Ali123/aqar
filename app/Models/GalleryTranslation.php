<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryTranslation extends Model
{
    public $timestamps = true;
    protected $fillable = ['title', 'description', 'alt_text'];
}
