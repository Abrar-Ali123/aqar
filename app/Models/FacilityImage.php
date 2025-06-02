<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FacilityImage extends Model
{
    protected $fillable = [
        'facility_id',
        'path',
        'type',
        'size',
        'order',
        'alt',
        'title',
        'metadata'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer',
        'metadata' => 'array'
    ];

    /**
     * العلاقة مع المنشأة
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * الحصول على الرابط العام للصورة
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * التحقق من نوع الصورة
     */
    public function isImage(): bool
    {
        return str_starts_with($this->type, 'image/');
    }

    /**
     * حذف الصورة من التخزين عند حذف السجل
     */
    protected static function booted()
    {
        static::deleting(function (FacilityImage $image) {
            Storage::disk('public')->delete($image->path);
        });
    }
}
