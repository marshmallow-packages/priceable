<?php

namespace Marshmallow\Product\Database\Seeds;

use Illuminate\Database\Seeder;
use Marshmallow\Product\Models\VatRate;

/**
 * php artisan db:seed --class=Marshmallow\\Product\\Database\\Seeds\\VatRatesSeeder
 */

class VatRatesSeeder extends Seeder
{
	protected $default_vat_rates = [
		['Geen', 0],
		['Laag', 9],
		['Hoog', 21],
	];
    

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->default_vat_rates as $rate) {
        	if (VatRate::where('name', $rate[0])->get()->first()) {
        		continue;
        	}

        	VatRate::create([
        		'name' => $rate[0],
        		'rate' => $rate[1],
        	]);
        }
    }
}
