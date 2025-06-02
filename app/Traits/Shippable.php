<?php
namespace App\Traits;
use App\Services\ShippingService;

trait Shippable
{
    public function createShipment($provider = null)
    {
        $service = new ShippingService();
        return $service->createShipment($this, $provider);
    }
}
