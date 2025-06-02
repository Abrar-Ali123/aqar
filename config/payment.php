<?php
return [
    'default' => env('PAYMENT_GATEWAY', 'dummy'), // يمكن تغييره إلى stcpay, tap, ...
    'gateways' => [
        'dummy' => [
            'name' => 'بوابة تجريبية',
            'api_key' => env('DUMMY_PAYMENT_KEY'),
        ],
        // أضف بوابات دفع أخرى هنا
        'stripe' => [
            'name' => 'Stripe',
            'api_key' => env('STRIPE_SECRET_KEY'),
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'currency' => env('PAYMENT_CURRENCY', 'SAR'),
        ],
    ],
];
