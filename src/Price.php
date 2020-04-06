<?php

namespace Marshmallow\Priceable;

use Money\Money;
use Money\Currency;
use NumberFormatter;
use Laravel\Cashier\Cashier;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class Price
{
	public function formatAmount ($amount, $currency = null)
	{
		return Cashier::formatAmount($amount, $currency);
	}

	/**
	 * Should by dynamic
	 */
	public function getCurrency ($currency = null)
	{
		if ($currency) {
			return $currency;
		}
		return 'EUR'; // This should not be hardcoded here
	}

	public function amount ($amount, $currency = null)
	{
		return round($amount / 100, 2);
	}

	public function ping ()
	{
		return 'pong';
	}
}
