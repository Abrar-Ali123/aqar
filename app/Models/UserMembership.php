<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    protected $fillable = [
        'user_id', 'membership_id', 'start_date', 'end_date', 'active'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
