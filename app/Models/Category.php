<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'icon',
        'image',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $translatedAttributes = [
        'name'
    ];

    // العلاقة مع الفئة الأب
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // العلاقة مع الفئات الفرعية
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // العلاقة مع المنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // العلاقة مع الترجمات
    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    // الحصول على الترجمة
    public function getTranslation($attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        return $this->translations()
            ->where('locale', $locale)
            ->value($attribute);
    }

    // نطاق للفئات النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // نطاق للفئات الرئيسية فقط
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
