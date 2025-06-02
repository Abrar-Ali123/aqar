<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayFee extends Model
{
    protected $fillable = ['gateway', 'fee_percent', 'fee_fixed'];
}
