<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Support\Str;

class PageTemplate extends Model implements HasMedia, Searchable
{
    use SoftDeletes, InteractsWithMedia;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'is_active',
        'is_default',
        'layout',
        'styles',
        'components',
        'settings',
        'preview_image',
        'category',
        'sort_order',
        'is_public',
        'author_id',
        'rating',
        'downloads',
        'version',
        'tags',
        'price',
        'features',
        'seo_title',
        'seo_description',
        'custom_css',
        'custom_js',
        'required_plugins'
    ];

    protected $casts = [
        'layout' => 'array',
        'styles' => 'array',
        'components' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_public' => 'boolean',
        'rating' => 'float',
        'downloads' => 'integer',
        'tags' => 'array',
        'features' => 'array',
        'required_plugins' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->name);
            }
        });

        static::created(function ($template) {
            Cache::tags(['templates'])->flush();
        });

        static::updated(function ($template) {
            Cache::tags(['templates'])->flush();
        });

        static::deleted(function ($template) {
            Cache::tags(['templates'])->flush();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preview')
            ->singleFile()
            ->withResponsiveImages()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, 300, 300)
                    ->nonQueued();
                
                $this->addMediaConversion('mobile')
                    ->fit(Manipulations::FIT_CONTAIN, 768, 1024)
                    ->nonQueued();
            });

        $this->addMediaCollection('screenshots')
            ->withResponsiveImages()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, 300, 300)
                    ->nonQueued();
            });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(TemplateReview::class);
    }

    public function categories()
    {
        return $this->belongsToMany(TemplateCategory::class);
    }

    public function analytics()
    {
        return $this->hasMany(TemplateAnalytic::class);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->name,
            route('website-templates.show', ['locale' => app()->getLocale(), 'template' => $this->id])
        );
    }

    /**
     * العلاقة مع صفحات المنشآت
     */
    public function pages()
    {
        return $this->hasMany(FacilityPage::class, 'template_id');
    }

    /**
     * الحصول على المكونات المتاحة في القالب
     */
    public function getAvailableComponents()
    {
        return collect($this->components)->map(function ($component) {
            return [
                'type' => $component['type'],
                'name' => $component['name'],
                'icon' => $component['icon'] ?? 'fas fa-puzzle-piece',
                'settings' => $component['settings'] ?? [],
                'default_content' => $component['default_content'] ?? [],
                'animations' => $component['animations'] ?? [],
                'responsive_settings' => $component['responsive_settings'] ?? [],
                'api_integrations' => $component['api_integrations'] ?? [],
                'interactions' => $component['interactions'] ?? [],
                'seo_settings' => $component['seo_settings'] ?? [],
                'performance_settings' => $component['performance_settings'] ?? [],
                'accessibility_settings' => $component['accessibility_settings'] ?? []
            ];
        });
    }

    /**
     * تحديث تخطيط القالب
     */
    public function updateLayout(array $layout)
    {
        $this->layout = $layout;
        $this->version = $this->version + 0.1;
        $this->save();
        
        // Create revision
        TemplateRevision::create([
            'template_id' => $this->id,
            'layout' => $layout,
            'version' => $this->version,
            'user_id' => auth()->id()
        ]);

        Cache::tags(['templates', 'template:'.$this->id])->flush();
    }

    /**
     * تحديث أنماط القالب
     */
    public function updateStyles(array $styles)
    {
        $this->styles = $styles;
        $this->version = $this->version + 0.1;
        $this->save();
        
        // Create revision
        TemplateRevision::create([
            'template_id' => $this->id,
            'styles' => $styles,
            'version' => $this->version,
            'user_id' => auth()->id()
        ]);

        Cache::tags(['templates', 'template:'.$this->id])->flush();
    }
}
