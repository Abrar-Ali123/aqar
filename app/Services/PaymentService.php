<?php
namespace App\Services;

class PaymentService
{
    public function pay($order, $gateway = null)
    {
        $gateway = $gateway ?? config('payment.default');
        // من هنا يتم استدعاء بوابة الدفع المناسبة
        // مثال: دمج مع بوابة تجريبية
        return [
            'status' => 'pending',
            'redirect_url' => route('payment.callback'),
        ];
    }
}
