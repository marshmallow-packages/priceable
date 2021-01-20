<?php

namespace Marshmallow\Priceable\Nova\Helpers;

class FieldNameHelper
{
    public static function priceLabel($label = 'Price')
    {
        return (config('priceable.nova.prices_are_including_vat'))
                    ? __($label . ' (incl VAT)')
                    : __($label . ' (excl VAT)');
    }
}
