<?php

namespace Marshmallow\Priceable\Models;

use Illuminate\Support\Str;
use App\Observers\PriceableObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Marshmallow\HelperFunctions\Traits\Observer;
use Marshmallow\HelperFunctions\Traits\ModelHasDefaults;
use Marshmallow\HelperFunctions\Facades\Builder as BuilderFacade;

class Price extends Model
{
    use Observer;
    use SoftDeletes;
    use ModelHasDefaults;

    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_till' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(self::getObserver());
    }

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
        return $this->price() . ' ' . Str::of(config('priceable.currency'))->upper();
    }

    public function pricePrependingCurrencyString()
    {
        return Str::of(config('priceable.currency'))->upper() . ' ' . $this->price();
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
    public function type()
    {
        return $this->belongsTo(config('priceable.models.price_type'), 'price_type_id');
    }

    public function vatrate()
    {
        return $this->belongsTo(config('priceable.models.vat'));
    }

    public function currency()
    {
        return $this->belongsTo(config('priceable.models.currency'));
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
    public function defaultAttributes(): array
    {
        return [
            'vatrate_id' => config('priceable.nova.defaults.vat_rates'),
            'currency_id' => config('priceable.nova.defaults.currencies'),
            'price_type_id' => config('priceable.nova.defaults.price_type'),
        ];
    }

    public static function getObserver(): string
    {
        return config('priceable.observers.price', PriceableObserver::class);
    }
}
