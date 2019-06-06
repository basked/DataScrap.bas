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

//Route::middleware('auth:api')->get('/cart', function (Request $request) {
// //   return $request->user();
//    return 'api basket';
//});
//
//
//Route::get('api/cart/bas', function (Request $request) {
//    return 'api basket';
//});

Route::get('/cart',function (){
    return 'api cart hello';
});