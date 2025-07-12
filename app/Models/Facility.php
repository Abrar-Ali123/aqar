<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Language;
use App\Models\BusinessCategory;
use App\Models\BusinessSector;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Facility extends Model implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    public array $translatedAttributes = ['name', 'description', 'business_description', 'business_keywords'];
    
    protected $fillable = [
        'logo',
        'cover',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'is_active',
        'is_featured',
        'facility_type_id',
        'business_category_id',
        'business_sector_id',
        'working_hours',
        'social_media',
        'website',
        'registration_number',
        'tax_number',
        'styles',
        'component_settings',
        'supported_locales',
        'slug',
        'rating',
        'reviews_count',
        'is_verified'
    ];

    /**
     * Get the owner of the facility.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users that belong to the facility.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'facility_user');
    }

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'working_hours' => 'array',
        'social_media' => 'array',
        'styles' => 'json',
        'component_settings' => 'json',
        'supported_locales' => 'array',
        'rating' => 'float',
        'reviews_count' => 'integer'
    ];

    /**
     * Get all products for the facility
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_facilities');
    }

    /**
     * Get facility type
     */
    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }

    /**
     * Get business category
     */
    public function businessCategory(): BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class);
    }

    public function getRatingAttribute($value): float
    {
        if (!$value) {
            return 0.0;
        }
        return is_numeric($value) ? (float) $value : 0.0;
    }

    /**
     * Get business sector
     */
    public function businessSector(): BelongsTo
    {
        return $this->belongsTo(BusinessSector::class);
    }

    /**
     * Get facility pages
     */
    public function pages()
    {
        return $this->hasMany(FacilityPage::class);
    }

    /**
     * Get facility template
     */
    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    /**
     * Get facility reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope a query to only include active facilities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the average rating for the facility
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()
                    ->where('is_approved', true)
                    ->avg('rating') ?? 0;
    }

    /**
     * Get the total number of reviews for the facility
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()
                    ->where('is_approved', true)
                    ->count();
    }

    /**
     * Get facility images
     */
    public function images(): HasMany
    {
        return $this->hasMany(FacilityImage::class);
    }

    /**
     * Get facility offers
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get facility services
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * Get facility events
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get facility news
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * Get facility brands
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    /**
     * التحقق من دعم اللغة المحددة
     *
     * @param string $locale رمز اللغة
     * @return bool
     */
    public function supportsLocale(string $locale): bool
    {
        return $this->translations()->where('locale', $locale)->exists();
    }

    /**
     * الحصول على إعدادات مكون محدد
     *
     * @param string $componentId معرف المكون
     * @return array
     */
    public function getComponentSettings(string $componentId): array
    {
        $settings = $this->component_settings ?? [];
        return $settings[$componentId] ?? [];
    }

    /**
     * تحديث إعدادات مكون محدد
     *
     * @param string $componentId معرف المكون
     * @param array $settings الإعدادات الجديدة
     * @return bool
     */
    public function updateComponentSettings(string $componentId, array $settings): bool
    {
        $allSettings = $this->component_settings ?? [];
        $allSettings[$componentId] = $settings;
        return $this->update(['component_settings' => $allSettings]);
    }

    /**
     * Check if facility has booking feature enabled
     */
    public function hasBooking(): bool
    {
        return $this->accepts_bookings ?? false;
    }

    /**
     * Check if facility has menu feature enabled
     */
    public function hasMenu(): bool
    {
        $products = $this->products;
        if($products->count() > 0){
            return true;
        }
        return false;
        //return $this->belongsToMany(Product::class, 'product_facilities')
        //            ->exists();
    }

    /**
     * Check if facility has gallery feature enabled
     */
    public function hasGallery(): bool
    {
        return $this->hasMany(FacilityImage::class)
                    ->select('id')
                    ->limit(1)
                    ->exists();
    }

    /**
     * Get facility customization settings
     */
    protected function getCustomizationAttribute()
    {
        $activePage = $this->pages()
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        if (!$activePage) {
            return (object) [
                'design_settings' => [
                    'primary_color' => '#4a5568',
                    'secondary_color' => '#718096',
                    'accent_color' => '#f56565',
                    'text_color' => '#2d3748',
                    'background_color' => '#ffffff',
                    'font_family' => 'Tajawal',
                    'heading_font' => 'Tajawal',
                    'button_style' => 'rounded',
                    'layout_style' => 'modern'
                ]
            ];
        }

        return (object) [
            'design_settings' => json_decode($activePage->design_settings ?? '{}', true) ?: [],
            'template_settings' => json_decode($activePage->template_settings ?? '{}', true) ?: []
        ];
    }
}
