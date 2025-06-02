<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'filters',
        'notify',
        'frequency',
        'last_notification_at',
    ];

    protected $casts = [
        'filters' => 'json',
        'notify' => 'boolean',
        'last_notification_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return route('search', array_merge(
            ['locale' => app()->getLocale()],
            json_decode($this->filters, true)
        ));
    }
}
