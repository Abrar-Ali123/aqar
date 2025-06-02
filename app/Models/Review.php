<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\DispatchesUniversalEvent;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Review extends Model implements HasMedia
{
    use HasTranslations, SoftDeletes, InteractsWithMedia, DispatchesUniversalEvent, LogsActivity;

    protected $fillable = [
        'user_id',
        'facility_id',
        'rating',
        'comment',
        'is_approved',
        'translations'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'translations' => 'array'
    ];

    /**
     * Get the user who wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the facility being reviewed
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Scope a query to only include approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('review-images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxFileSize(2 * 1024 * 1024); // 2MB
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeRated($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    public function scopeMaxRating($query, $rating)
    {
        return $query->where('rating', '<=', $rating);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForReviewable($query, $type, $id)
    {
        return $query->where('reviewable_type', $type)
            ->where('reviewable_id', $id);
    }
}
