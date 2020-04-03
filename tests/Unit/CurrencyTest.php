<?php

namespace Tests\Unit;

use Tests\TestCase;
use Marshmallow\Priceable\Facades\Price;
use Marshmallow\Priceable\Models\Currency;

class CurrencyTest extends TestCase
{
    public function testItCanBeCreated()
    {
    	$currency = Currency::create([
    		'name' => 'EURO'
    	]);
    	$this->assertInstanceOf(Currency::class, $currency);
    }
}
