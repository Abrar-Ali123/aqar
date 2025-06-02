<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UniversalEntityEvent;
use App\Listeners\UniversalEntityEventListener;

class UniversalEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UniversalEntityEvent::class => [
            UniversalEntityEventListener::class,
        ],
    ];
}
