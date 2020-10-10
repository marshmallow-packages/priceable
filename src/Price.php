<?php

namespace Marshmallow\Priceable;

use Laravel\Cashier\Cashier;

class Price
{
    public function formatAmount($amount, $currency = null)
    {
        return Cashier::formatAmount($amount, $currency);
    }

    public function amount($amount, $currency = null)
    {
        return round($amount / 100, 2);
    }
}
