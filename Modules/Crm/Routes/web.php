<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('crm')->group(function() {
    Route::get('/crm', 'CrmController@index');

    Route::get('/shop', 'ShopController@create');
    Route::post('/shop', 'ShopController@store');
    Route::delete('/shop/{id}','ShopController@destroy')->name('ShopDestroy');


    Route::get('/product', 'ProductController@create');
    Route::post('/product', 'ProductController@store');
    Route::delete('/product/{id}','ProductController@destroy')->name('ProductDestroy');

});

