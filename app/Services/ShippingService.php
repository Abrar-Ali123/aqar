<?php
namespace App\Services;

class ShippingService
{
    public function createShipment($order, $provider = null)
    {
        $provider = $provider ?? config('shipping.default');
        // Stub: استدعاء مزود الشحن المناسب
        return [
            'status' => 'created',
            'tracking_number' => 'TRK-'.rand(1000,9999),
        ];
    }
}
