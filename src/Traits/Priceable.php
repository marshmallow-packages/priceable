<?php

namespace Marshmallow\Priceable\Traits;

use Illuminate\Support\Facades\Config;
use Marshmallow\Priceable\Models\Price;
use Marshmallow\Priceable\Models\PriceType;
use Illuminate\Database\Eloquent\Collection;
use Marshmallow\Priceable\Facades\Price as PriceHelper;

trait Priceable
{
    protected $price_type;

    public function priceType(PriceType $type)
    {
        /**
         * We need to clone this item because otherwise
         * it will only return the provided price type
         * prices and i will not return the default price
         * anymore when you just call ->currentPrice() directly
         */
        $self = clone $this;
        $self->price_type = $type;
        return $self;
    }

    public function currentPrice($multiplier = null)
    {
        $price = $this->price();
        if (!$price) {
            return 0;
        }
        $price = $price->price();
        if ($multiplier) {
            $price = $price * $multiplier;
        }
        return $price;
    }

    public function discountedFrom($multiplier = null)
    {
        $price = $this->getHighestPrice()->price();
        if ($multiplier) {
            $price = $price * $multiplier;
        }
        return $price;
    }

    public function price()
    {
        $prices = $this->availablePrices()->get();
        if ($prices->count() > 1) {
            $price = $this->desideWhichPriceToUse($prices);
        } else {
            $price = $prices->first();
        }

        return $price;
    }

    public function getPriceHelper()
    {
        $price = $this->price();
        return PriceHelper::make(
            $this->price()->vatrate,
            $this->price()->currency,
            $price->display_price,
            ($price->display_price === $price->price_including_vat)
        );
    }

    public function isDiscounted()
    {
        if ($prices = $this->hasMultiplePrices()) {
            $highest = $this->desideWhichPriceToUse($prices, 'highest');
            $lowest = $this->desideWhichPriceToUse($prices, 'lowest');

            if ($highest->price() != $lowest->price()) {
                return true;
            }
        }

        return false;
    }

    public function getHighestPrice()
    {
        if ($prices = $this->hasMultiplePrices()) {
            return $this->desideWhichPriceToUse($prices, 'highest');
        }

        return $this->price();
    }

    public function hasPrice()
    {
        return ($this->availablePrices()->count() > 0);
    }

    protected function hasMultiplePrices()
    {
        $prices = $this->availablePrices()->get();
        if ($prices->count() <= 1) {
            return null;
        }

        return $prices;
    }

    protected function desideWhichPriceToUse(Collection $prices, $action = '')
    {
        $action = ($action) ?: config('priceable.on_multiple_prices');

        switch ($action) {
            case 'highest':
                return $prices->sortByDesc('display_price')->first();

                break;
            case 'lowest':
                return $prices->sortBy('display_price')->first();

                break;
            case 'eldest':
                return $prices->sortBy('valid_from')->first();

                break;
            case 'newest':
                return $prices->sortByDesc('valid_from')->first();

                break;
        }

        return $prices->first();
    }

    /**
     * This function makes it possible to call this
     * like an attribute. Eq; $product->price
     */
    public function getPriceAttribute()
    {
        $type = $this->getPriceType();
        if (! $this->price($type)) {
            return;
        }

        return $this->price($type)->price();
    }

    protected function getPriceType()
    {
        if ($this->price_type) {
            return $this->price_type;
        }

        return $this->getDefaultPriceType();
    }

    protected function getDefaultPriceType()
    {
        return PriceType::find(config('priceable.detault_price_type'));
    }

    /**
     * Relationships
     */
    public function availablePrices()
    {
        return $this->prices()->where('price_type_id', $this->getPriceType()->id)->currentlyActive();
    }

    public function prices(PriceType $type = null)
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
