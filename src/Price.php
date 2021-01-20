<?php

namespace Marshmallow\Priceable;

use Money\Money;
use Money\Currency;
use Laravel\Cashier\Cashier;
use Marshmallow\Priceable\Models\VatRate;
use Marshmallow\Priceable\Models\Currency as PricableCurrency;

class Price
{
    public $vatrate;

    public $currency;

    public $display_amount;

    public $price_excluding_vat;

    public $price_including_vat;

    public $vat_amount;

    public function make(VatRate $vatrate, PricableCurrency $currency, int $display_amount, bool $display_is_including_vat)
    {
        $this->vatrate = $vatrate;
        $this->currency = $currency;
        $this->display_amount = $display_amount;

        if ($display_is_including_vat) {

            /**
             * The added price is including the VAT. We need to calculate
             * the price without the VAT.
             */
            $price_excluding_vat = round(($display_amount / (100 + $vatrate->rate)) * 100);
        } else {
            $price_excluding_vat = $display_amount;
        }

        $this->price_excluding_vat = $price_excluding_vat;
        $this->price_including_vat = round($price_excluding_vat * $vatrate->multiplier());
        $this->vat_amount = $this->price_including_vat - $this->price_excluding_vat;

        return $this;
    }

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
