<?php

use Illuminate\Support\Facades\Route;
use Marshmallow\Priceable\Http\Controllers\SetCurrencyController;

Route::middleware(['web'])
    ->get(
        'set-currency/{currency:id}',
        SetCurrencyController::class
    )->name('set-currency');
