<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\DispatchesUniversalEvent;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasTranslations, SoftDeletes, DispatchesUniversalEvent, LogsActivity;

    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'facility_id',
        'user_id',
        'translations'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'translations' => 'array'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function getDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date);
    }
}
