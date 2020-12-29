<?php

namespace Marshmallow\Priceable\Facades;

class Price extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Priceable\Price::class;
    }
}
