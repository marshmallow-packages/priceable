<?php

namespace Marshmallow\Priceable\Seeders;

use Illuminate\Database\Seeder;
use Marshmallow\Priceable\Models\Currency;

/**
 * php artisan db:seed --class=Marshmallow\\Priceable\\Seeders\\CurrencySeeder
 */

class CurrencySeeder extends Seeder
{
    protected $default_currencies = [
        'Euro' => 'EUR',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->default_currencies as $currency => $iso) {
            if (Currency::where('name', $currency)->get()->first()) {
                continue;
            }

            Currency::create([
                'name' => $currency,
                'iso_4217' => $iso,
            ]);
        }
    }
}
