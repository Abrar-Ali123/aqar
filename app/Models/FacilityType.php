<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FacilityType extends Model implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    /**
     * الحقول القابلة للترجمة
     */
    public $translatedAttributes = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'custom_translations'
    ];

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'slug',
        'icon',
        'is_active',
        'parent_id',
        'order',
        'settings',
        'metadata',
        'color_code',
        'icon_class',
        'cover_image',
        'permissions',
        'roles',
        'menu_structure',
        'dashboard_layout',
        'has_products',
        'product_settings',
        'has_customers',
        'customer_settings',
        'has_financial',
        'financial_settings',
        'report_templates',
        'analytics_settings',
        'integration_settings',
        'api_settings',
        'notification_settings'
    ];

    /**
     * الحقول التي يجب معاملتها كـ JSON
     */
    protected $casts = [
        'settings' => 'json',
        'metadata' => 'json',
        'permissions' => 'json',
        'roles' => 'json',
        'menu_structure' => 'json',
        'dashboard_layout' => 'json',
        'product_settings' => 'json',
        'customer_settings' => 'json',
        'financial_settings' => 'json',
        'report_templates' => 'json',
        'analytics_settings' => 'json',
        'integration_settings' => 'json',
        'api_settings' => 'json',
        'notification_settings' => 'json',
        'is_active' => 'boolean',
        'has_products' => 'boolean',
        'has_customers' => 'boolean',
        'has_financial' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->translate(app()->getLocale())->name);
            }
        });
    }

    /**
     * Get the parent facility type
     */
    public function parent()
    {
        return $this->belongsTo(FacilityType::class, 'parent_id');
    }

    /**
     * Get the child facility types
     */
    public function children()
    {
        return $this->hasMany(FacilityType::class, 'parent_id');
    }

    /**
     * Get all facilities of this type
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class, 'facility_type_id');
    }

    /**
     * Get the default settings merged with type-specific settings
     */
    public function getFullSettingsAttribute()
    {
        $defaultSettings = config('facility_types.default_settings', []);
        return array_merge($defaultSettings, $this->settings ?? []);
    }

    /**
     * Get the complete hierarchy path
     */
    public function getHierarchyPathAttribute()
    {
        $path = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent);
            $parent = $parent->parent;
        }

        return $path;
    }

    /**
     * Check if the type has specific capability
     */
    public function hasCapability($capability)
    {
        $settings = $this->settings ?? [];
        return isset($settings['capabilities'][$capability]) && $settings['capabilities'][$capability];
    }

    /**
     * Get available product types for this facility type
     */
    public function availableProductTypes()
    {
        return $this->belongsToMany(ProductType::class, 'facility_type_product_type');
    }

    /**
     * Get the validation rules for this facility type
     */
    public function getValidationRules()
    {
        return $this->settings['validation_rules'] ?? [];
    }

    /**
     * Get the complete menu structure
     */
    public function getCompleteMenuStructure()
    {
        $defaultMenu = config('facility_types.default_menu', []);
        return array_merge($defaultMenu, $this->menu_structure ?? []);
    }

    /**
     * Scope a query to only include active types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include root types (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get all descendants of the facility type
     */
    public function descendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }
        
        return $descendants;
    }

    /**
     * Get all ancestors of the facility type
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }
}
