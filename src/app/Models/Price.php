<?php

namespace Marshmallow\Priceable\Models;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\Priceable\Models\VatRate;
use Marshmallow\Priceable\Models\Currency;
use Marshmallow\HelperFunctions\Traits\Observer;
use Marshmallow\HelperFunctions\Traits\ModelHasDefaults;

class Price extends Model
{
	use ModelHasDefaults, Observer;

	protected $guarded = [];

	protected $casts = [
		'valid_from' => 'datetime',
		'valid_till' => 'datetime',
	];

	public function vatrate ()
	{
		return $this->belongsTo(VatRate::class);
	}

	public function currency ()
	{
		return $this->belongsTo(Currency::class);
	}

	public function priceable ()
	{
		return $this->morphTo();
	}

	public static function getObserver (): string
	{
		return \Marshmallow\Priceable\Observers\PriceObserver::class;
	}

	public function defaultAttributes ()
	{
		return [
			'vatrate_id' => config('priceable.nova.defaults.vat_rates'),
			'currency_id' => config('priceable.nova.defaults.currencies'),
		];
	}

	public function __saving ()
	{
		if (!config('priceable.nova.prices_are_including_vat')) {
    		$price_excluding_vat = $this->display_price;
    	} else {
    		/**
    		 * The added price is including the VAT. We need to calculate
    		 * the price without the VAT.
    		 */
    		$price_excluding_vat = ($this->display_price / (100 + $this->vatrate->rate)) * 100;
    	}

        $this->price_excluding_vat = $price_excluding_vat;
        $this->price_including_vat = $price_excluding_vat * $this->vatrate->multiplier();
        $this->vat_amount = $this->price_including_vat - $this->price_excluding_vat;
	}
}