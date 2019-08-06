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

Route::middleware('auth:api')->get('/skills', function (Request $request) {
//    return $request->user();
    dd('api skills');
});

Route::group(['prefix' => 'skills'], function () {

    Route::get('/basket', function (Request $request) {
        dd($request);
    });

    Route::get('/bass', function (Request $request) {
        dd($request);
    });



    Route::resource('/languages', 'LanguageController');
});
