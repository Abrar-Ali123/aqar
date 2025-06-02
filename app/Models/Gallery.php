<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Gallery extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['title', 'description', 'alt_text'];

    protected $fillable = [
        'path',
        'type',
        'size',
        'mime_type',
        'galleryable_id',
        'galleryable_type',
        'order',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean'
    ];

    public function galleryable()
    {
        return $this->morphTo();
    }
}
