<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'required',
        'category_id',
        'icon',
        'Symbol',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'required' => 'boolean',
    ];

    /**
     * Get the translations for the attribute.
     */
    public function translations()
    {
        return $this->hasMany(AttributeTranslation::class);
    }

    /**
     * Get the category that owns the attribute.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the attribute values for the attribute.
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the attribute name based on locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->name : '';
    }

    /**
     * Get the attribute symbol based on locale.
     */
    public function getSymbolTextAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->symbol : $this->Symbol;
    }
}
