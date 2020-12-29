<?php

namespace Marshmallow\Priceable\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
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
        //
    ];

    public static $group = 'Pricing';

    public static $group_icon = '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="var(--sidebar-icon)" d="M10,20 C15.5228475,20 20,15.5228475 20,10 C20,4.4771525 15.5228475,0 10,0 C4.4771525,0 0,4.4771525 0,10 C0,15.5228475 4.4771525,20 10,20 Z M11,15 L11,17 L9,17 L9,15 L6,15 L6,13 L10.5838882,13 L11.9970707,13 C12.5621186,13 13,12.5522847 13,12 C13,11.4438648 12.5509732,11 11.9970707,11 L10.5838882,11 L8,11 C6.34314575,11 5,9.65685425 5,8 C5,6.34314575 6.34314575,5 8,5 L9,5 L9,3 L11,3 L11,5 L14,5 L14,7 L9.41464715,7 L7.99077797,7 C7.45097518,7 7,7.44771525 7,8 C7,8.55613518 7.44358641,9 7.99077797,9 L9.41464715,9 L12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 L11,15 Z"></path></g></g></svg>';

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
            BelongsTo::make('VatRate')->withoutTrashed(),
            BelongsTo::make('Currency')->withoutTrashed(),
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
