<?php

Route::group(['namespace' => 'Marshmallow\Cart\Http\Controllers'], function(){
	Route::post('/', 'CartController@index');
});