<?php

namespace Marshmallow\Priceable\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Marshmallow\Priceable\Nova\VatRate;
use Marshmallow\Priceable\Nova\PriceType;
use Marshmallow\Priceable\Nova\Helpers\FieldNameHelper;
use Marshmallow\Priceable\Nova\Currency as CurrencyResource;

class Price extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\Priceable\Models\Price';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        //
    ];

    public static $group = 'Pricing';

    public static $group_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path fill="var(--sidebar-icon)" d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            MorphTo::make(__('Priceable'), 'priceable')->types(
                config('priceable.nova.resources')
            ),
            BelongsTo::make(__('Price Type'), 'type', config('priceable.resources.price_type'))->withoutTrashed(),
            BelongsTo::make(__('Vat rate'), 'vatrate', config('priceable.resources.vat'))->withoutTrashed(),
            BelongsTo::make(__('Currency'), 'currency', config('priceable.resources.currency'))->withoutTrashed(),
            Currency::make(FieldNameHelper::priceLabel(), 'display_price')->displayUsing(function ($value) {
                return \Marshmallow\Priceable\Facades\Price::formatAmount($value);
            })->resolveUsing(function ($value) {
                return \Marshmallow\Priceable\Facades\Price::amount($value);
            }),
            DateTime::make(__('Valid from'), 'valid_from'),
            DateTime::make(__('Valid till'), 'valid_till'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
