<?php

namespace App\Enums;

enum ProductType: string
{
    case Sale = 'sale';
    case Rent = 'rent';
    case Subscription = 'subscription';

    public function label(): string
    {
        return match($this) {
            self::Sale => 'بيع',
            self::Rent => 'إيجار',
            self::Subscription => 'اشتراك',
        };
    }
}
