<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserSignupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Tenant::observe(\App\Observers\TenantActionObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
