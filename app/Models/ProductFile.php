<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductFile extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'path',
        'type',
        'size',
        'is_downloadable',
        'download_count',
        'metadata'
    ];

    protected $casts = [
        'size' => 'integer',
        'is_downloadable' => 'boolean',
        'download_count' => 'integer',
        'metadata' => 'array'
    ];

    /**
     * العلاقة مع المنتج
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * الحصول على رابط التحميل المؤقت
     */
    public function getTemporaryUrl(int $minutes = 5): string
    {
        return Storage::disk('private')->temporaryUrl(
            $this->path,
            now()->addMinutes($minutes)
        );
    }

    /**
     * تسجيل عملية تحميل
     */
    public function recordDownload(): void
    {
        $this->increment('download_count');
    }

    /**
     * حذف الملف من التخزين عند حذف السجل
     */
    protected static function booted()
    {
        static::deleting(function (ProductFile $file) {
            Storage::disk('private')->delete($file->path);
        });
    }
}
