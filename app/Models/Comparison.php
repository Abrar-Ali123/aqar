<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comparison extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id',
        'is_public',
        'translations'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'translations' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'comparison_products')
            ->withTimestamps();
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isOwnedBy($userId)
    {
        return $this->user_id === $userId;
    }

    public function isPublic()
    {
        return $this->is_public;
    }

    public function isPrivate()
    {
        return !$this->is_public;
    }
}
