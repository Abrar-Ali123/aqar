<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReview extends Model
{
    protected $fillable = ['transaction_id', 'reviewed_by', 'status', 'notes'];
    public function transaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'transaction_id');
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
