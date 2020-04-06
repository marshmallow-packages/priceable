<?php

namespace Tests\Unit;

use Tests\TestCase;
use Marshmallow\Priceable\Facades\Price;
use Marshmallow\Priceable\Models\VatRate;

class VatRateTest extends TestCase
{
    public function testItCanBeCreatedWithNameAndRateOnly()
    {
    	$rate = VatRate::create([
    		'name' => 'High',
    		'rate' => 21,
    	]);
        $this->assertInstanceOf(VatRate::class, $rate);
    }

    public function testItRequiresNameWhenCreating ()
    {
    	try {
    		$rate = VatRate::create([
	    		'rate' => 21,
	    	]);
    	} catch (\Illuminate\Database\QueryException $e) {
    		$this->assertStringContainsString("Field 'name'", $e->getMessage());
    	}
    }

    public function testItRequiresRateWhenCreating ()
    {
    	try {
    		$rate = VatRate::create([
	    		'name' => 'High',
	    	]);
    	} catch (\Illuminate\Database\QueryException $e) {
    		$this->assertStringContainsString("Field 'rate'", $e->getMessage());
    	}
    }

    public function testIsHasASlugAfterCreating ()
    {
    	$rate = VatRate::create([
    		'name' => 'High',
    		'rate' => 21,
    	]);

        $this->assertEquals('high', $rate->slug);
    }

    public function testIsHasAUniqueSlugAfterCreating ()
    {
    	$rate = VatRate::create([
    		'name' => 'High',
    		'rate' => 21,
    	]);

    	$another_rate = VatRate::create([
    		'name' => 'High',
    		'rate' => 21,
    	]);

        $this->assertEquals('high-1', $another_rate->slug);
    }

    public function testItReturnsACorrectMultiplierValue ()
    {
    	$rate = VatRate::create([
    		'name' => 'High',
    		'rate' => 21,
    	]);

    	$this->assertEquals(1.21, $rate->multiplier());
    }
}
