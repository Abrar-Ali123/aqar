<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPageReview extends Model
{
    protected $fillable = [
        'facility_page_id',
        'name',
        'email',
        'review',
        'rating',
        'approved',
    ];

    public function page()
    {
        return $this->belongsTo(FacilityPage::class, 'facility_page_id');
    }
}
