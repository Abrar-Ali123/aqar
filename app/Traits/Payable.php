<?php
namespace App\Traits;
use App\Services\PaymentService;

trait Payable
{
    public function pay($gateway = null)
    {
        $service = new PaymentService();
        return $service->pay($this, $gateway);
    }
}
