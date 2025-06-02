<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    protected $fillable = ['name', 'logo', 'active'];
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
