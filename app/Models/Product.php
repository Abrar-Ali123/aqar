<?php

namespace App\Models;

use App\Enums\ProductType;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements TranslatableContract, HasMedia
{
    use HasFactory, Translatable, InteractsWithMedia, SoftDeletes;

    /**
     * الحقول القابلة للترجمة
     */
    public $translatedAttributes = [
        'name',
        'description',
        'meta_title',
        'meta_description'
    ];

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'category_id',
        'price',
        'is_active',
        'order',
        'sku',
        'type',
        'thumbnail',
        'owner_user_id',
        'seller_user_id',
        'metadata'
    ];

    /**
     * جدول الترجمات
     */
    protected $translationForeignKey = 'product_id';
    protected $translationModel = 'App\Models\ProductTranslation';

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'order' => 'integer',
        'type' => ProductType::class,
        'metadata' => 'array'
    ];

    /**
     * العلاقات المحملة تلقائياً
     */
    protected $with = ['translations'];

    /**
     * تهيئة النموذج
     */
    protected static function boot()
    {
        parent::boot();

        // ترتيب افتراضي للمنتجات
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc')
                   ->orderBy('created_at', 'desc');
        });
    }

    /**
     * إعداد مجموعات الوسائط
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')
             ->useDisk('public')
             ->withResponsiveImages();
    }

    /*
    |--------------------------------------------------------------------------
    | العلاقات
    |--------------------------------------------------------------------------
    */

    /**
     * العلاقة مع المنشأة
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'product_facilities');
    }

    /**
     * Get the main facility for the product
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the ratings for the product
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * العلاقة مع الفئة
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    /**
     * العلاقة مع صور المنتج
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    /**
     * العلاقة مع الملفات
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }

    /**
     * العلاقة مع المالك
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * العلاقة مع البائع
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    /**
     * العلاقة مع التقييمات
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * العلاقة مع التعليقات
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * العلاقة مع المفضلة
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    /*
    |--------------------------------------------------------------------------
    | نطاقات الاستعلام
    |--------------------------------------------------------------------------
    */

    /**
     * الحصول على المنتجات حسب المنشأة
     */
    public function scopeByFacility($query, $facilityId): Builder
    {
        return $query->whereHas('facilities', function ($query) use ($facilityId) {
            $query->where('facility_id', $facilityId);
        });
    }

    /**
     * الحصول على المنتجات حسب الفئة
     */
    public function scopeByCategory($query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * تحسين استرجاع المنتجات مع الترجمات
     */
    public function scopeWithTranslations($query, $locale = null): Builder
    {
        return $query->with(['translations' => function ($query) use ($locale) {
            if ($locale) {
                $query->where('locale', $locale);
            }
        }]);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type): Builder
    {
        return $query->where('type', $type);
    }

    /*
    |--------------------------------------------------------------------------
    | الدوال المساعدة
    |--------------------------------------------------------------------------
    */

    /**
     * تحديث ترجمات المنتج
     */
    public function updateTranslations(array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $this->translations()->updateOrCreate(
                ['locale' => $locale],
                $data
            );
        }

        $this->clearCache();
    }

    /**
     * مسح الكاش المتعلق بالمنتج
     */
    public function clearCache(): void
    {
        Cache::tags(['products', "product_{$this->id}"])->flush();
        
        if ($this->facilities()->exists()) {
            $this->facilities()->each(function ($facility) {
                Cache::tags(['facility.' . $facility->id])->flush();
            });
        }
    }

    /**
     * Get the attribute values for the product.
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the product name based on locale.
     */
    public function getNameAttribute()
    {
        return $this->translate(app()->getLocale())?->name ?? '';
    }

    /**
     * Get the product description based on locale.
     */
    public function getDescriptionAttribute()
    {
        return $this->translate(app()->getLocale())?->description ?? '';
    }

    /**
     * Get attribute value by code.
     */
    public function getAttributeValue($code)
    {
        return $this->attributeValues()
            ->whereHas('attribute', function($query) use ($code) {
                $query->where('code', $code);
            })
            ->first()?->value;
    }

    /**
     * Get the location data if available.
     */
    public function getLocationAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'maps_url' => $this->google_maps_url
            ];
        }
        return null;
    }

    /**
     * الحصول على متوسط التقييمات
     */
    public function getAverageRating(): float
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->avg('rating') ?? 0.0;
    }

    /**
     * الحصول على عدد التقييمات
     */
    public function getReviewsCount(): int
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->count();
    }

    /**
     * التحقق مما إذا كان المنتج عقاراً
     */
    public function isProperty(): bool
    {
        return in_array($this->type, ['sale', 'rent']);
    }

    /**
     * التحقق مما إذا كان المنتج خدمة
     */
    public function isService(): bool
    {
        return $this->type === 'service';
    }

    /**
     * التحقق مما إذا كان المنتج رقمياً
     */
    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    /**
     * التحقق مما إذا كان المنتج فعلياً
     */
    public function isPhysical(): bool
    {
        return $this->type === 'physical';
    }

    public function getImageUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }
}
