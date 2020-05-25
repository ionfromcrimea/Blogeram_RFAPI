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

    Route::get('blogers/{bloger}/relationships/news', function(){
        return true;
    })->name('blogers.relationships.news');

    Route::get('blogers/{bloger}/news', function(){
        return true;
    })->name('blogers.news');

});
