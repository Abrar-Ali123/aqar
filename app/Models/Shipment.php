<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\ShippingCompany;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'tracking_number', 'status', 'recipient_name', 'recipient_phone', 'address', 'shipping_cost', 'details'
    ];
    protected $casts = [
        'details' => 'array',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }
}
