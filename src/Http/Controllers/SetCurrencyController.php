<?php

namespace Marshmallow\Priceable\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Marshmallow\Priceable\Models\Currency;

class SetCurrencyController extends Controller
{
    public function __invoke(Currency $currency, Request $request)
    {
        $request->setUserCurrency($currency);

        return redirect()->back();
    }
}
