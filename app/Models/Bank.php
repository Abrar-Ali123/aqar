<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'swift_code',
        'logo',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
