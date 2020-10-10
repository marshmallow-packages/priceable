<?php

namespace Marshmallow\Priceable\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Marshmallow\Priceable\Models\Price;

trait Priceable
{
    public function price()
    {
        $prices = $this->availablePrices;
        if ($prices->count() > 1) {
            $price = $this->desideWhichPriceToUse($prices);
        } else {
            $price = $prices->first();
        }

        return $price;
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
        return ($this->availablePrices->count() > 0);
    }

    protected function hasMultiplePrices()
    {
        $prices = $this->availablePrices;
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
        if (! $this->price()) {
            return;
        }

        return $this->price()->price();
    }

    /**
     * Relationships
     */
    public function availablePrices()
    {
        return $this->prices()->currentlyActive();
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
