<?php

namespace Marshmallow\Priceable\Database\Seeds;

use Illuminate\Database\Seeder;
use Marshmallow\Priceable\Models\Vat;
use Marshmallow\Priceable\Models\Currency;

/**
 * php artisan db:seed --class=Marshmallow\\Priceable\\Database\\Seeds\\CurrencySeeder
 */

class CurrencySeeder extends Seeder
{
	protected $default_currencies = [
		'Euro'
	];
    

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->default_currencies as $currency) {
        	if (Currency::where('name', $currency)->get()->first()) {
        		continue;
        	}

        	Currency::create([
        		'name' => $currency
        	]);
        }
    }
}