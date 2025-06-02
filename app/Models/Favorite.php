<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id',
        'favorable_type',
        'favorable_id',
        'translations'
    ];

    protected $casts = [
        'translations' => 'array'
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع العنصر المفضل
     */
    public function favorable()
    {
        return $this->morphTo();
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('favorable_type', $type);
    }

    public function isOwnedBy($userId)
    {
        return $this->user_id === $userId;
    }
}
