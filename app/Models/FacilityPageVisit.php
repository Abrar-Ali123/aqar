<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPageVisit extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'facility_page_id',
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    public function page()
    {
        return $this->belongsTo(FacilityPage::class, 'facility_page_id');
    }
}
