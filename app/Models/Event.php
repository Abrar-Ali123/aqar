<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Event extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public array $translatedAttributes = ['title', 'description'];

    protected $fillable = [
        'facility_id',
        'start_date',
        'end_date',
        'image',
        'is_active',
        'max_attendees'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'max_attendees' => 'integer'
    ];

    /**
     * Get the facility that owns the event
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Scope a query to only include active events
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '>=', now());
    }

    /**
     * Scope a query to only include upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc');
    }
}
