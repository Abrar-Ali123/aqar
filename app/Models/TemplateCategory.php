<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TemplateCategory extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'sort_order',
        'icon',
        'is_active',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function parent()
    {
        return $this->belongsTo(TemplateCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TemplateCategory::class, 'parent_id');
    }

    public function templates()
    {
        return $this->belongsToMany(PageTemplate::class);
    }

    public function getActiveTemplates()
    {
        return $this->templates()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->singleFile();

        $this->addMediaCollection('banner')
            ->singleFile();
    }
}
