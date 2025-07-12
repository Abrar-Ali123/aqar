<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Prefix
    |--------------------------------------------------------------------------
    |
    | This config option allows you to define a default prefix for your icons.
    | When defining components in your blade views, the prefix will be your alias.
    | This can be useful if you want to avoid class name collisions with other packages.
    |
    */
    'prefix' => 'heroicon',

    /*
    |--------------------------------------------------------------------------
    | Default Set
    |--------------------------------------------------------------------------
    |
    | This config option allows you to define a default icon set.
    | Supported: "outline", "solid"
    |
    */
    'default' => 'solid',

    /*
    |--------------------------------------------------------------------------
    | Class Attributes
    |--------------------------------------------------------------------------
    |
    | Here you can define default class attributes. The key is the icon type
    | and the value is an array of classes. These will be merged with any
    | classes specified when rendering the icon component.
    |
    */
    'class' => [
        'outline' => 'w-6 h-6',
        'solid' => 'w-6 h-6',
    ],
];
