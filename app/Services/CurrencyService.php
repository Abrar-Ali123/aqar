<?php
namespace App\Services;

class CurrencyService
{
    public static function convert($amount, $from, $to)
    {
        // مثال: سعر صرف ثابت (يمكن لاحقاً ربطه بمصدر خارجي)
        $rates = [
            'SAR' => 1,
            'USD' => 0.27,
            'EUR' => 0.25,
        ];
        $rate = isset($rates[$to]) ? $rates[$to] / $rates[$from] : 1;
        return [$amount * $rate, $rate];
    }
}
