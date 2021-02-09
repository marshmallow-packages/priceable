<?php

return [

    'detault_price_type' => 1,

	/**
	 * Overschrijf nova settings. Door zoveel mogelijk beheerbaar
	 * te maken in deze config, deste kleiner is de kans dat de
	 * Nova Stubs overschreven gaan worden. Dit is met het oogpunt
	 * op updates wel erg fijn.
	 */
	'nova' => [
		'prices_are_including_vat' => true,
		'defaults' => [
			'currencies' => 1,
			'vat_rates' => 2,
		],

		/**
		 * Add the resources that are priceable here so Nova
		 * knows where to look for them.
		 */
		'resources' => [
			\Marshmallow\Product\Nova\Product::class,
		]
	],

	/**
	 * When we find more then one price on a model when calling
	 * the $product->price() method, how should we deside which
	 * price to use.
	 */
	'on_multiple_prices' => 'lowest', // highest, lowest, eldest, newest

	'public_excluding_vat' => env('PRICEABLE_PUBLIC_EXCLUDING_VAT', false),
];
