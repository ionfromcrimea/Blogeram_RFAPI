<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('blogers', 'BlogersController');

    Route::apiResource('news', 'NewsController');

    Route::get('blogers/{bloger}/relationships/news', 'BlogersNewsRelationshipsController@index')
        ->name('blogers.relationships.news');

    Route::patch('blogers/{bloger}/relationships/news', 'BlogersNewsRelationshipsController@update')
        ->name('blogers.relationships.news');

    Route::get('blogers/{bloger}/news', 'BlogersNewsRelatedController@index')
        ->name('blogers.news');

    Route::get('news/{news}/relationships/blogers', 'NewsBlogersRelationshipsController@index')
        ->name('news.relationships.blogers');

    Route::patch('news/{news}/relationships/blogers', 'NewsBlogersRelationshipsController@update')
        ->name('news.relationships.blogers');

    Route::get('news/{news}/blogers', 'NewsBlogersRelatedController@index')
        ->name('news.blogers');

});
