<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'gateway', 'subscription_id', 'status', 'amount', 'currency', 'interval', 'started_at', 'ends_at', 'details'
    ];
    protected $casts = [
        'details' => 'array',
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
