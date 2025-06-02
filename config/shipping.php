<?php
return [
    'default' => env('SHIPPING_PROVIDER', 'dummy'),
    'providers' => [
        'dummy' => [
            'name' => 'شركة شحن تجريبية',
            'api_key' => env('DUMMY_SHIPPING_KEY'),
        ],
        // أضف شركات شحن أخرى هنا
    ],
];
