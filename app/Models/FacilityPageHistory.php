<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPageHistory extends Model
{
    protected $fillable = [
        'facility_page_id',
        'user_id',
        'action',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(FacilityPage::class, 'facility_page_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
