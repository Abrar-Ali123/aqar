<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFacilityRole extends Pivot
{
    protected $table = 'user_facility_role';

    protected $fillable = [
        'user_id',
        'facility_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
