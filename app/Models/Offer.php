<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Offer extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public array $translatedAttributes = ['title', 'description'];

    protected $fillable = [
        'facility_id',
        'discount',
        'start_date',
        'end_date',
        'image',
        'is_active'
    ];

    protected $casts = [
        'discount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the facility that owns the offer
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Scope a query to only include active offers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function ($query) {
                        $query->where('end_date', '>=', now())
                              ->orWhereNull('end_date');
                    });
    }
}
