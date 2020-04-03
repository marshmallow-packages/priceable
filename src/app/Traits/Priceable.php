<?php

namespace Marshmallow\Priceable\Traits;

use Illuminate\Support\Facades\Config;
use Marshmallow\Priceable\Models\Price;

trait Priceable
{
    public function prices ()
	{
		return $this->morphMany(Price::class, 'priceable');
	}
}
