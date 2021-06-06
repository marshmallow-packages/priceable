<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Marshmallow\Priceable\Models\Price;
use Marshmallow\Priceable\Models\VatRate;
use Marshmallow\Priceable\Models\Currency;

class PriceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'Marshmallow\\Priceable\\Database\\Seeds\\VatRatesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Marshmallow\\Priceable\\Database\\Seeds\\CurrencySeeder']);
        Config::set('priceable.currency', 'EUR');
        Config::set('app.locale', 'nl');
        Config::set('priceable.currency_locale', 'nl');
    }

    public function createTestableHighRate()
    {
        return VatRate::create([
            'name' => 'Testable High Rate',
            'rate' => 21,
        ]);
    }

    public function createPrice($data = [])
    {
        $price = factory(Price::class)->create(array_merge([
            'vatrate_id' => $this->createTestableHighRate()->id,
            'currency_id' => Currency::get()->random()->id,
            'display_price' => 100,
        ], $data));

        return $price->fresh();
    }

    public function testItHasOneVatRate()
    {
        $price = $this->createPrice();
        $this->assertInstanceOf(VatRate::class, $price->vatrate);
    }

    public function testItHasOneCurrency()
    {
        $price = $this->createPrice();
        $this->assertInstanceOf(Currency::class, $price->currency);
    }

    public function testItReturnsAnObserver()
    {
        $price = $this->createPrice();
        $this->assertIsString($price->getObserver());
    }

    public function testItDefaultAttributesIsArray()
    {
        $price = $this->createPrice();
        $this->assertIsArray($price->defaultAttributes());
    }

    public function testItHasAMorphRelastionship()
    {
        $price = $this->createPrice();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphTo::class, $price->priceable());
    }

    public function testItImplementsTheMagicSavingMethod()
    {
        $price = $this->createPrice();
        $this->assertTrue(method_exists($price, '__saving'));
    }

    public function testItCalculatesPriceExcludingVatCorrectlyFromIncludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();

        $this->assertEquals($price->excludingVat(), 82.64);
    }

    public function testItCalculatesPriceIncludingVatCorrectlyFromIncludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();

        $this->assertEquals($price->includingVat(), 100);
    }

    public function testItCalculatesVatCorrectlyFromIncludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();

        $this->assertEquals($price->vat(), 17.36);
    }

    public function testItCalculatesPriceExcludingVatCorrectlyFromExcludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', false);
        $price = $this->createPrice();

        $this->assertEquals($price->excludingVat(), 100);
    }

    public function testItCalculatesPriceIncludingVatCorrectlyFromExcludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', false);
        $price = $this->createPrice();

        $this->assertEquals($price->includingVat(), 121);
    }

    public function testItCalculatesVatCorrectlyFromExcludingVatSetting()
    {
        Config::set('priceable.nova.prices_are_including_vat', false);
        $price = $this->createPrice();

        $this->assertEquals($price->vat(), 21);
    }

    public function testItCanReturnAFormattedAmount()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();
        $this->assertEquals($price->formatPrice(), '€ 100,00');
    }

    public function testItCanReturnAFormattedAmountExcludingVat()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        Config::set('priceable.public_excluding_vat', true);
        $price = $this->createPrice();
        $this->assertEquals($price->formatPrice(), '€ 82,64');
    }

    public function testItcanReturnANonFormattedAmount()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();
        $this->assertEquals($price->price(), 100);
    }

    public function testItcanReturnANonFormattedAmountExcludingVat()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        Config::set('priceable.public_excluding_vat', true);
        $price = $this->createPrice();
        $this->assertEquals($price->price(), 82.64);
    }

    public function testItStoresTheAmountsAsCentsInTheDatabase()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->display_price, 10000);
    }

    public function testItCanGetThePriceAsIncludingVatByTheConfigAttribute()
    {
        Config::set('priceable.nova.prices_are_including_vat', false);
        $price = $this->createPrice();
        $this->assertEquals($price->price(), 121);
    }

    public function testItCanGetThePriceAsExcludingVatByTheConfigAttribute()
    {
        Config::set('priceable.nova.prices_are_including_vat', true);
        $price = $this->createPrice();
        $this->assertEquals($price->price(), 100);
    }

    public function testItCanGetExcludedVatFormattedAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->formatExcludingVat(), '€ 82,64');
    }

    public function testItCanGetExcludedVatAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->excludingVat(), 82.64);
    }

    public function testItCanGetIncludedVatFormattedAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->formatIncludingVat(), '€ 100,00');
    }

    public function testItCanGetIncludedVatAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->includingVat(), 100);
    }

    public function testItCanGetVatFormattedAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->formatVat(), '€ 17,36');
    }

    public function testItCanGetVatAmount()
    {
        $price = $this->createPrice();
        $this->assertEquals($price->vat(), 17.36);
    }

    public function testItImplementsScopeCurrentlyActive()
    {
        $price = $this->createPrice();
        $another_price = $this->createPrice([
            'valid_till' => Carbon::yesterday()
        ]);

        $all_prices = Price::where('priceable_id', 1)->get();
        $scoped_prices = Price::where('priceable_id', 1)->currentlyActive()->get();

        $this->assertNotSame($all_prices->count(), $scoped_prices->count());
    }
}
