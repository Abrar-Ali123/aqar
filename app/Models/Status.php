<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'color',
        'icon',
        'order',
        'type',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
