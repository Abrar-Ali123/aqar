<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'gateway', 'transaction_id', 'status', 'amount', 'currency', 'user_id', 'details'
    ];
    protected $casts = [
        'details' => 'array',
        'amount' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
