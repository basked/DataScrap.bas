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

use Illuminate\Support\Facades\DB;
use Modules\Pars\Entities\Category;

Route::prefix('pars')->group(function () {
//    Route::get('/', 'ParsController@index');
//   Route::get('/guzzle', 'ParsController@scrapGuzzle');
//   Route::get('/curl', 'ParsController@scrapCurl');

    Route::get('/', 'ParsController@index');


    Route::get('/shops', 'ShopController@index')->name('ShopIndex');
    Route::get('/shop', 'ShopController@create')->name('ShopCreate');
    Route::post('/shop', 'ShopController@store')->name('ShopStore');
    Route::delete('/shop/{id}', 'ShopController@destroy')->name('ShopDestroy');
    Route::get('/shops/test', 'ShopController@test')->name('ShopTest');

    // Shop API
    Route::get('/api/shops{patams?}','ApiShopController@index');
    Route::post('/api/shops_insert','ApiShopController@store');
    Route::put('/api/shops_update/{id}','ApiShopController@update');
    Route::delete('/api/shops_delete/{id}','ApiShopController@destroy');



    Route::get('/categories', 'CategoryController@index')->name('CategoryIndex');
    Route::get('/categories_products', 'CategoryController@categoriesProducts')->name('CategoriesProducts');


    Route::get('/catsshop', 'CategoryController@categoriesForShop')->name('CategoriesForShop');
    Route::get('/category', 'CategoryController@create')->name('CategoryCreate');
    Route::post('/category', 'CategoryController@store')->name('CategoryStore');
    Route::delete('/category/{id}', 'CategoryController@destroy')->name('CategoryDestroy');
    // парсинг категорий
    Route::get('categories/pars', 'CategoryController@parsCategories')->name('CategoriesPars');
    Route::get('categories/updateProductsCnt', 'CategoryController@CategoriesUpdate')->name('CategoriesUpdate');;

    Route::get('/inactcategory/{id}', 'CategoryController@inactive')->name('CategoryInactive');
    Route::get('/actcategory/{id}', 'CategoryController@active')->name('CategoryActive');
    Route::get('/check_act_category/{ids}', 'CategoryController@activeCheck')->name('CategoryActiveCheck');
    Route::get('/categories/page', function () {

      
    }
    );

    // Товары
    Route::get('/products', 'ProductController@index')->name('ProductIndex');
    Route::get('/products/pars', 'ProductController@productsPars')->name('ProductsPars');
    Route::get('/products/category_pars/{id?}', 'ProductController@categoryPars')->name('ProdСategoryPars');
    Route::get('/products/categories_pars', 'ProductController@categoriesPars')->name('ProdСategoriesPars');
    Route::get('/products/test', function () {
       $db= DB::connection('mysql_sam')->table('s_pars_main_5')->select()->get();
       dd($db);

    });

    //Акции
    Route::get('/actions/test', function () {

       $products = \Modules\Pars\Entities\Product::where('name','LIKE','%JVC LT-42M450%')->first() ;//        $p=12;
    echo $products->name.'<br>';
     foreach ( $products->actions()->get() as $action){
         echo $action->name.'<br>';;
     }
//        \Modules\Pars\Entities\Product::destroy(1);
//        $product= new \Modules\Pars\Entities\Product();
//        $product->product_id=$p;
//        $product->name='Basked LMC';
//        $product->category_id=111;
//        $product->brand='basked';
//        $product->price=777;
//        $product->save();
//        \Modules\Pars\Entities\Action::destroy(1);
//       $action= new \Modules\Pars\Entities\Action();
//      $action->action_id=11;
//      $action->name='Discount1';
//      $action->type='dicount';
//
//      $action->save();
//       $action->products()->attach($product->id);

    });
});


