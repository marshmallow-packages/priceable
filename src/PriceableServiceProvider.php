<?php

namespace Marshmallow\Priceable;

use Illuminate\Support\ServiceProvider;

class PriceableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/../config/priceable.php',
            'priceable'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');

        $this->publishes([
            __DIR__.'/../config/priceable.php' => config_path('priceable.php'),
        ], 'config');
    }
}
