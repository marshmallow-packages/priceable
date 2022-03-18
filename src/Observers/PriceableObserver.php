<?php

namespace Marshmallow\Priceable\Observers;

use Marshmallow\Priceable\Models\Price;

class PriceableObserver
{
    public function saving(Price $price)
    {
        if (config('priceable.nova.prices_are_including_vat')) {

            /**
             * The added price is including the VAT. We need to calculate
             * the price without the VAT.
             */
            $price_excluding_vat = ($price->display_price / (100 + $price->vatrate->rate)) * 100;
        } else {
            $price_excluding_vat = $price->display_price;
        }

        $price->price_excluding_vat = $price_excluding_vat;
        $price->price_including_vat = $price_excluding_vat * $price->vatrate->multiplier();
        $price->vat_amount = $price->price_including_vat - $price->price_excluding_vat;
    }
}
