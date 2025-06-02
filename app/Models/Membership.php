<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'name', 'price', 'duration_days', 'features', 'active'
    ];
    protected $casts = [
        'features' => 'array',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_memberships')
            ->withPivot(['start_date', 'end_date', 'active'])
            ->withTimestamps();
    }
}
