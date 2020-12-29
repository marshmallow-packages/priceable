<?php

namespace Marshmallow\Priceable\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Marshmallow\HelperFunctions\Traits\Observer;
use Marshmallow\HelperFunctions\Traits\ModelHasDefaults;
use Marshmallow\HelperFunctions\Facades\Builder as BuilderFacade;

class Price extends Model
{
    use ModelHasDefaults, Observer, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_till' => 'datetime',
    ];

    protected function formatAmount($amount, $currency = null)
    {
        return \Marshmallow\Priceable\Facades\Price::formatAmount($amount, $currency);
    }

    protected function amount($amount, $currency = null)
    {
        return \Marshmallow\Priceable\Facades\Price::amount($amount, $currency);
    }

    /**
     * This will make sure that the submitted amount in Nova
     * is multiplied by 100 so we can store it in cents.
     * @param [type] $amount [description]
     */
    protected function setDisplayPriceAttribute(float $amount)
    {
        $this->attributes['display_price'] = $amount * 100;
    }

    /**
     * This function can be used on the front-end.
     * @return string Formatted price
     */
    public function formatPrice()
    {
        if (config('priceable.public_excluding_vat')) {
            return $this->formatAmount($this->price_excluding_vat);
        }

        return $this->formatAmount($this->price_including_vat);
    }

    public function price()
    {
        if (config('priceable.public_excluding_vat')) {
            return $this->amount($this->price_excluding_vat);
        }

        return $this->amount($this->price_including_vat);
    }

    public function priceAppendingCurrencyString()
    {
        return $this->price() . ' ' . Str::of(env('CASHIER_CURRENCY'))->upper();
    }

    public function pricePrependingCurrencyString()
    {
        return Str::of(env('CASHIER_CURRENCY'))->upper() . ' ' . $this->price();
    }

    public function formatExcludingVat()
    {
        return $this->formatAmount($this->price_excluding_vat);
    }

    public function excludingVat()
    {
        return $this->amount($this->price_excluding_vat);
    }

    public function formatIncludingVat()
    {
        return $this->formatAmount($this->price_including_vat);
    }

    public function includingVat()
    {
        return $this->amount($this->price_including_vat);
    }

    public function formatVat()
    {
        return $this->formatAmount($this->vat_amount);
    }

    public function vat()
    {
        return $this->amount($this->vat_amount);
    }

    /**
     * Scopes
     */
    public function scopeCurrentlyActive(Builder $builder)
    {
        BuilderFacade::published($builder);
    }

    /**
     * Relationships
     */
    public function vatrate()
    {
        return $this->belongsTo(VatRate::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function priceable()
    {
        return $this->morphTo();
    }

    /**
     * For a price we need to make sure we always have
     * a VAT rate and a Currency. Selecting them everytime
     * in Nova is a hassle, therefor we set some default
     * that come from the config.
     * @return array Array with default attributes
     */
    public function defaultAttributes()
    {
        return [
            'vatrate_id' => config('priceable.nova.defaults.vat_rates'),
            'currency_id' => config('priceable.nova.defaults.currencies'),
        ];
    }

    /**
     * Observer will make sure the "hidden" columns
     * will be filled when creating or updating
     * a price.
     */
    public static function getObserver(): string
    {
        return \Marshmallow\Priceable\Observers\PriceObserver::class;
    }

    public function __saving()
    {
        if (config('priceable.nova.prices_are_including_vat')) {

            /**
    		 * The added price is including the VAT. We need to calculate
    		 * the price without the VAT.
    		 */
            $price_excluding_vat = ($this->display_price / (100 + $this->vatrate->rate)) * 100;
        } else {
            $price_excluding_vat = $this->display_price;
        }

        $this->price_excluding_vat = $price_excluding_vat;
        $this->price_including_vat = $price_excluding_vat * $this->vatrate->multiplier();
        $this->vat_amount = $this->price_including_vat - $this->price_excluding_vat;
    }
}
