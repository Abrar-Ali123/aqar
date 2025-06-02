<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\DispatchesUniversalEvent;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasTranslations, SoftDeletes, InteractsWithMedia, DispatchesUniversalEvent, LogsActivity;

    protected $fillable = [
        'author', 'latitude', 'longitude', 'google_maps_url', 'facility_id', 'image', 'seller_user_id', 'project_type', 'translations'
    ];

    protected $casts = [
        'translations' => 'array'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('project-attachments')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])
            ->maxFileSize(10 * 1024 * 1024); // 10MB
    }
}
