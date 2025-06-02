<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'type',
        'priority',
        'parent_id',
        'read_at',
        'translations'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'translations' => 'array'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
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

    public function scopeWithPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->orWhere('recipient_id', $userId);
        });
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

    public function isDirectMessage()
    {
        return $this->type === 'direct';
    }

    public function isSupportMessage()
    {
        return $this->type === 'support';
    }

    public function isSystemMessage()
    {
        return $this->type === 'system';
    }

    public function hasHighPriority()
    {
        return $this->priority === 'high';
    }

    public function hasNormalPriority()
    {
        return $this->priority === 'normal';
    }

    public function hasLowPriority()
    {
        return $this->priority === 'low';
    }
}
