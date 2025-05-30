<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'locale',
        'name',
    ];

    public $timestamps = false;

    /**
     * Get the category that owns the translation.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
