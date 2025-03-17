<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    protected $fillable = [
        'parent_id',
        'image',
    ];

    public $translatedAttributes = ['name'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the category name based on locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();

        return $translation ? $translation->name : '';
    }

    /**
     * Get all parent categories.
     */
    public function getAllParents()
    {
        $parents = collect([]);
        $parent = $this->parent;

        while ($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents->reverse();
    }

    /**
     * Get all child categories recursively.
     */
    public function getAllChildren()
    {
        $children = $this->children;

        foreach ($this->children as $child) {
            $children = $children->merge($child->getAllChildren());
        }

        return $children;
    }
}
