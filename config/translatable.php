<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'direction' => [
            'ar' => 'rtl',
            'en' => 'ltr',
        ],
        'required_languages' => ['ar'],
        'fallback_locale' => 'ar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Types
    |--------------------------------------------------------------------------
    */
    'field_types' => [
        'text' => [
            'component' => 'input',
            'type' => 'text',
            'class' => 'form-control',
        ],
        'textarea' => [
            'component' => 'textarea',
            'class' => 'form-control',
            'rows' => 3,
        ],
        'editor' => [
            'component' => 'editor',
            'class' => 'form-control tinymce',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Messages
    |--------------------------------------------------------------------------
    */
    'messages' => [
        'required' => 'حقل :attribute مطلوب للغة :language',
        'max' => 'حقل :attribute يجب ألا يتجاوز :max حرف للغة :language',
        'min' => 'حقل :attribute يجب ألا يقل عن :min حرف للغة :language',
    ],
];
