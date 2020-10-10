<?php

namespace Marshmallow\Priceable\Nova\Helpers;

class FieldNameHelper
{
    public static function priceLabel()
    {
        return (config('priceable.nova.prices_are_including_vat'))
                    ? __('Price (incl VAT)')
                    : __('Price (excl VAT)');
    }
}
