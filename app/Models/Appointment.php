<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Appointment extends Model implements TranslatableContract
{
    use HasTranslations, SoftDeletes, LogsActivity;

    public array $translatedAttributes = ['description'];
    
    protected $fillable = [
        'user_id',
        'facility_id',
        'appointment_time',
        'status',
        'type',
        'metadata',
        'translations'
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'metadata' => 'json',
        'translations' => 'array'
    ];

    // العلاقات الأساسية
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    // العلاقات الإضافية لنظام HR
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'position_id');
    }

    // Scopes للتصفية
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_time', '>', now());
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute(): bool
    {
        return $this->appointment_time->isPast() && $this->status !== 'completed';
    }

    public function getTypeNameAttribute(): string
    {
        $types = [
            'attendance' => 'حضور وانصراف',
            'leave' => 'إجازة',
            'training' => 'تدريب',
            'interview' => 'مقابلة',
            'evaluation' => 'تقييم'
        ];

        return $types[$this->type] ?? $this->type;
    }
}
