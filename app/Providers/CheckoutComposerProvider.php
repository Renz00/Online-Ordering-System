<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CheckoutComposerProvider extends ServiceProvider
{
    //Make sure you add this custom Provider in config/app.php
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //This calls the CheckoutComposer class whenever a page loads
        view()->composer('layouts/partials/checkout', 'App\Http\Composers\CheckoutComposer');
    }
}
