<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    // Get changes between old and new values
    public function getChangesAttribute()
    {
        if ($this->action !== 'updated') {
            return [];
        }

        $changes = [];
        foreach ($this->payload as $key => $value) {
            if (!array_key_exists($key, $this->payload) || $this->payload[$key] !== $value) {
                $changes[$key] = [
                    'old' => $this->payload[$key] ?? null,
                    'new' => $value
                ];
            }
        }

        return $changes;
    }
}
