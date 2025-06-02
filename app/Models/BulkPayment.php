<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkPayment extends Model
{
    protected $fillable = [
        'reference', 'created_by', 'total_amount', 'currency', 'status', 'details'
    ];
    protected $casts = [
        'details' => 'array',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
