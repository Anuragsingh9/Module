<?php

Route::group(['middleware' => 'web', 'prefix' => 'cocktail', 'namespace' => 'Modules\Cocktail\Http\Controllers'], function()
{
    Route::get('/', 'CocktailController@index');
    Route::get('check','TestController@create');
});
