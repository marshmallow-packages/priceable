<?php

return [

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
	]
];