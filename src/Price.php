<?php

namespace Marshmallow\Priceable;

use Money\Money;
use Money\Currency;
use Laravel\Cashier\Cashier;

class Price
{
    public function formatAmount($amount, $currency = null)
    {
        return Cashier::formatAmount($amount, $currency);
    }

    public function getMoney($amount, Currency $currency = null)
    {
        if (! $currency) {
            $currency = new Currency('eur');
        }

        return new Money($amount, $currency);
    }

    public function amount($amount, $currency = null)
    {
        return round($amount / 100, 2);
    }
}
