<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStage extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'color',
        'icon',
        'order',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'stage_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
