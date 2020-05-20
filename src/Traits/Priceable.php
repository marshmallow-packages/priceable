<?php

namespace Marshmallow\Priceable\Traits;

use Illuminate\Support\Facades\Config;
use Marshmallow\Priceable\Models\Price;
use Illuminate\Database\Eloquent\Collection;

trait Priceable
{
	public function price ()
	{
		$prices = $this->availablePrices;

		if ($prices->count() > 1) {
			$price = $this->desideWhichPriceToUse($prices);
		} else {
			$price = $prices->first();
		}

		return $price->price();
	}

	protected function desideWhichPriceToUse (Collection $prices, $action = '')
	{
		$action = ($action) ?: config('priceable.on_multiple_prices');

		switch ($action) {
			case 'highest':
				return $prices->sortByDesc('display_price')->first();
				break;
			case 'lowest':
				return $prices->sortBy('display_price')->first();
				break;
			case 'eldest':
				return $prices->sortBy('valid_from')->first();
				break;
			case 'newest':
				return $prices->sortByDesc('valid_from')->first();
				break;
		}

		return $prices->first();
	}

	/**
	 * This function makes it possible to call this
	 * like an attribute. Eq; $product->price
	 */
	public function getPriceAttribute ()
	{
		return $this->price();
	}


	/**
	 * Relationships
	 */
	public function availablePrices ()
	{
		return $this->prices()->currentlyActive();
	}

    public function prices ()
	{
		return $this->morphMany(Price::class, 'priceable');
	}
}
