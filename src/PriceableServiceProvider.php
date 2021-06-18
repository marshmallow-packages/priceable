<?php

namespace Marshmallow\Priceable;

use Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Marshmallow\Priceable\Models\Currency;

class PriceableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/priceable.php',
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
        Request::macro('setUserCurrency', function (Currency $currency) {
            Session::put('user-currency', $currency->id);
        });

        Request::macro('getUserCurrency', function () {
            if ($session = Session::get('user-currency')) {
                return Currency::find($session);
            }

            $currency = Currency::find(config('priceable.nova.defaults.currencies'));
            if (!$currency) {
                $currency = Currency::first();
            }

            return $currency;
        });

        $this->publishes([
            __DIR__ . '/../config/priceable.php' => config_path('priceable.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
    }
}
