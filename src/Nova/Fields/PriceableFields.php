<?php

namespace Marshmallow\Priceable\Nova\Fields;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;

class PriceableFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Text::make('vat id'),
            Number::make('price excluding vat'),
            Number::make('price including vat'),
            DateTime::make('valid from'),
            DateTime::make('valid till'),
        ];
    }
}