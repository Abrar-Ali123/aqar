<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // أحداث النظام الأساسية
        \App\Events\UniversalEntityEvent::class => [
            \App\Listeners\UniversalEntityEventListener::class,
        ],
        \App\Events\StatusChanged::class => [
            \App\Listeners\HandleAutomatedStatus::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
