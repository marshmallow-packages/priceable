<?php

namespace Marshmallow\Priceable\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Marshmallow\Priceable\Nova\Helpers\FieldNameHelper;

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
        'name',
    ];

    public static $group = 'Pricing';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            MorphTo::make('Priceable', 'priceable')->types(
                config('priceable.nova.resources')
            ),
            BelongsTo::make('VatRate'),
            BelongsTo::make('Currency'),
            Currency::make(FieldNameHelper::priceLabel(), 'display_price')->displayUsing(function ($value) {
                return \Marshmallow\Priceable\Facades\Price::formatAmount($value);
            })->resolveUsing(function ($value) {
                return \Marshmallow\Priceable\Facades\Price::amount($value);
            }),
            DateTime::make('Valid from'),
            DateTime::make('Valid till'),
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
