<?php

Route::group(['middleware' => 'web', 'prefix' => 'newsletter', 'namespace' => 'Modules\Newsletter\Http\Controllers'], function()
{
    Route::get('/', 'NewsletterController@index');

    Route::group(['prefix' => 'news'], function () {
        Route::post('add', 'NewsController@store');
        Route::get('getnewss/{status}','NewsController@getNewss');
        Route::get('getnews/status','NewsController@newsStatusCount');
//        Route::put('update', 'NewsController@update');
//        Route::get('transition', 'NewsController@applyTransition');
//        Route::get('counts', 'NewsController@getCounts'); // to get the counts of news when click on news management
//        Route::get('state', 'NewsController@getNews');

        Route::group(['prefix' => 'review'], function () {
            Route::get('review', 'ReviewController@getReviews');
            Route::post('review/create/{news}', 'ReviewController@store'); // form request
            Route::get('getnewsreview/{news}','ReviewController@newsReview');
            Route::get('searchNews/{title}','ReviewController@searchNews');

//            Route::put('review/update/description', 'ReviewController@addDescription'); // form request
            Route::put('review/update/send', 'ReviewController@send'); // form request
            Route::get('review/count', 'ReviewController@getReviewsCount');
            Route::get('review/count/vissible','ReviewController@countReviewBySent');
        });
    });
});
