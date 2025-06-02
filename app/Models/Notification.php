<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'read_at',
        'translations'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'translations' => 'array'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function markAsRead()
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread()
    {
        if ($this->isRead()) {
            $this->update(['read_at' => null]);
        }
    }
}
