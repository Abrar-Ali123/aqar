<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BusinessSector;

class BusinessSectorTranslation extends Model
{
    protected $fillable = [
        'business_sector_id',
        'locale',
        'name',
        'description'
    ];

    public function businessSector(): BelongsTo
    {
        return $this->belongsTo(BusinessSector::class);
    }
}
