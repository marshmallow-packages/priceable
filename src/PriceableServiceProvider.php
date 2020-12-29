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
        /**
         * Views
         */
        // $this->loadViewsFrom(__DIR__.'/views', 'marshmallow');

        $this->loadFactoriesFrom(__DIR__.'/../database/factories');

        $this->publishes([
            // __DIR__.'/views' => resource_path('views/vendor/marshmallow'),
        ]);


        /**
         * Routes
         */
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                // InstallCommand::class,
            ]);
        }
    }
}
