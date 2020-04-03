<?php

namespace Marshmallow\Product\Database\Seeds;

use Illuminate\Database\Seeder;
use Marshmallow\Product\Models\Vat;
use Marshmallow\Product\Models\Currency;

/**
 * php artisan db:seed --class=Marshmallow\\Product\\Database\\Seeds\\CurrencySeeder
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
