<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\AccountManagementComponent;

class LivewireServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Livewire::component('account-management', AccountManagementComponent::class);
    }
}
