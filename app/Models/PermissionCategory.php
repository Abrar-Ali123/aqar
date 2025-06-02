<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionCategory extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'order',
        'translations',
        'is_active'
    ];

    protected $casts = [
        'translations' => 'array',
        'is_active' => 'boolean'
    ];

    // Get parent category
    public function parent()
    {
        return $this->belongsTo(PermissionCategory::class, 'parent_id');
    }

    // Get child categories
    public function children()
    {
        return $this->hasMany(PermissionCategory::class, 'parent_id');
    }

    // Get all permissions in this category
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'category_id');
    }

    // Get all permissions including children categories
    public function allPermissions()
    {
        return $this->permissions()->union(
            Permission::whereIn('category_id', $this->children()->pluck('id'))
        );
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get full hierarchy path
    public function getPathAttribute()
    {
        $path = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent);
            $parent = $parent->parent;
        }

        return $path;
    }
}
