<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPagePermission extends Model
{
    protected $fillable = [
        'facility_page_id',
        'user_id',
        'role',
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
