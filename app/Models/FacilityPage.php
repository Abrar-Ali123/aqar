<?php
namespace App\Models;

use App\Traits\FacilityPageCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class FacilityPage extends Model
{
    use SoftDeletes, FacilityPageCache;

    protected $fillable = [
        'facility_id',
        'title',
        'slug',
        'order',
        'is_active',
        'translations',
        'settings',
        'template_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => AsCollection::class,
        'settings' => AsCollection::class,
        'meta' => AsCollection::class,
        'sections' => AsCollection::class,
    ];

    protected $with = ['template'];

    protected static function booted()
    {
        static::saved(function ($page) {
            $page->clearAllCache();
        });

        static::deleted(function ($page) {
            $page->clearAllCache();
        });
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function attributeValues()
    {
        return $this->morphMany(AttributeValue::class, 'attributeable');
    }

    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    public function reviews()
    {
        return $this->hasMany(FacilityPageReview::class);
    }

    public function visits()
    {
        return $this->hasMany(FacilityPageVisit::class);
    }

    public function histories()
    {
        return $this->hasMany(FacilityPageHistory::class);
    }

    public function permissions()
    {
        return $this->hasMany(FacilityPagePermission::class);
    }

    /**
     * جلب محتوى الصفحة مع التخزين المؤقت
     */
    public function getContent(array $sections = null)
    {
        $cacheKey = $sections ? 'sections_' . implode('_', $sections) : 'full';
        return Cache::remember(
            $this->getCacheKey() . '_' . $cacheKey,
            now()->addHours(24),
            function () use ($sections) {
                $query = $this->with([
                    'facility',
                    'template',
                    'attributeValues',
                    'reviews' => function ($q) {
                        $q->latest()->take(5);
                    }
                ]);

                if ($sections) {
                    $query->whereJsonContains('sections', $sections);
                }

                return $query->first();
            }
        );
    }

    /**
     * تحديث إحصائيات الصفحة
     */
    public function updateStats(): void
    {
        $stats = [
            'views' => $this->visits()->count(),
            'reviews' => $this->reviews()->count(),
            'avg_rating' => $this->reviews()->avg('rating') ?? 0,
            'last_visit' => $this->visits()->latest()->first()?->created_at,
        ];

        $this->update(['meta->stats' => $stats]);
        $this->clearSectionCache('stats');
    }
}
