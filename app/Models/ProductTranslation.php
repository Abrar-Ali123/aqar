<?php

// app/ProductTranslation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale',
    ];

    /**
     * Get the product that owns the translation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
