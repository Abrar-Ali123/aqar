<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $fillable = [
        'sector_id',
        'name',
        'name_en',
        'slug',
        'icon',
        'description',
        'description_en',
        'is_active',
        'sort_order',
        'features',
        'recommended_components',
        'default_settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'recommended_components' => 'array',
        'default_settings' => 'array'
    ];

    public function sector()
    {
        return $this->belongsTo(BusinessSector::class, 'sector_id');
    }

    public function subcategories()
    {
        return $this->hasMany(BusinessSubcategory::class, 'category_id');
    }

    public function modules()
    {
        return $this->morphMany(CategoryModule::class, 'categorizable');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'category_id');
    }

    public function templates()
    {
        return $this->hasMany(PageTemplate::class, 'category_id');
    }

    /**
     * الحصول على القوالب الموصى بها لهذا النوع من الأعمال
     */
    public function getRecommendedTemplates()
    {
        return $this->templates()
            ->where('is_active', true)
            ->orderBy('rating', 'desc')
            ->orderBy('downloads', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * إنشاء قالب افتراضي للفئة
     */
    public function createDefaultTemplate($name, $description = null)
    {
        return $this->templates()->create([
            'name' => $name,
            'description' => $description,
            'is_active' => true,
            'components' => $this->recommended_components,
            'settings' => $this->default_settings,
            'features' => $this->features
        ]);
    }
}
