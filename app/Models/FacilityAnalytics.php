<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityAnalytics extends Model
{
    protected $fillable = [
        'facility_id',
        'page_id',
        'visitor_id',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'referrer',
        'duration',
        'interaction_data'
    ];

    protected $casts = [
        'interaction_data' => 'json'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function page()
    {
        return $this->belongsTo(FacilityPage::class);
    }
}
