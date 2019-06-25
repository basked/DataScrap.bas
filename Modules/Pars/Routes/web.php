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

use Curl\Curl;
use Curl\MultiCurl;
use Illuminate\Support\Facades\DB;
use Modules\Pars\Entities\Category;
use Modules\Pars\Entities\Product;
use Symfony\Component\DomCrawler\Crawler;

Route::prefix('pars')->group(function () {
//    Route::get('/', 'ParsController@index');
//   Route::get('/guzzle', 'ParsController@scrapGuzzle');
//   Route::get('/curl', 'ParsController@scrapCurl');


    // Shop API
    Route::get('/api/shops{patams?}', 'ApiShopController@index');
    Route::post('/api/shops_insert', 'ApiShopController@store');
    Route::put('/api/shops_update/{id}', 'ApiShopController@update');
    Route::delete('/api/shops_delete/{id}', 'ApiShopController@destroy');


    // Category API

    Route::get('/api/categories/{patams?}', 'ApiCategoryController@index');
    Route::post('/api/categories_insert/', 'ApiCategoryController@store');
    Route::put('/api/categories_update/{id}', 'ApiCategoryController@update');
    Route::delete('/api/categories_delete/{id}', 'ApiCategoryController@destroy');
    Route::get('api/categories_keys/', 'ApiCategoryController@categories_keys');


    // Product API
    Route::get('/api/products{patams?}', 'ApiProductController@index');
    Route::post('/api/products_insert', 'ApiProductController@store');
    Route::put('/api/products_update/{id}', 'ApiProductController@update');
    Route::delete('/api/products_delete/{id}', 'ApiProductController@destroy');


    Route::get('/', 'ParsController@index');


    Route::get('/shops', 'ShopController@index')->name('ShopIndex');
    Route::get('/shop', 'ShopController@create')->name('ShopCreate');
    Route::post('/shop', 'ShopController@store')->name('ShopStore');
    Route::delete('/shop/{id}', 'ShopController@destroy')->name('ShopDestroy');
    Route::get('/shops/test', 'ShopController@test')->name('ShopTest');


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
    Route::get('/products/categories_null_pars', 'ProductController@categoriesNullPars')->name('ProdСategoriesNullPars');
    Route::get('/products/products_import_to_sam', 'ProductController@productsImportToSam')->name('ProdImportToSam');

    Route::get('/products/test', function () {

        $base_url = 'https://www.21vek.by/washing_machines/page:3/';
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setProxy('172.16.15.33', 3128, 'gt-asup6', 'teksab');
        $data = $curl->post($base_url);
        $crawler = new Crawler($data);
        $products_all=[];
        $products_all=  $crawler->filter('#j-search_result')->filter('ul>li')->each(function (Crawler $node, $i) use ($products_all) {

            $products['data-code'] = $node->filter('dl>dt')->filter('.result__root')->filter(' .g-price.result__price.cr-price__in>span')->filter('.g-item-data.j-item-data')->attr('data-code');
            $products['data-name'] = $node->filter('dl>dt')->filter('.result__root')->filter(' .g-price.result__price.cr-price__in>span')->filter('.g-item-data.j-item-data')->attr('data-name');
            $products['data-producer_name'] = $node->filter('dl>dt')->filter('.result__root')->filter(' .g-price.result__price.cr-price__in>span')->filter('.g-item-data.j-item-data')->attr('data-producer_name');
            $products['data-price'] = $node->filter('dl>dt')->filter('.result__root')->filter(' .g-price.result__price.cr-price__in>span')->filter('.g-item-data.j-item-data')->attr('data-price');
            $products['data-old_price'] = $node->filter('dl>dt')->filter('.result__root')->filter(' .g-price.result__price.cr-price__in>span')->filter('.g-item-data.j-item-data')->attr('data-old_price');
           echo '<pre>';
            print_r($products);
            echo '</pre>';
        }) ;
//        dd($products_all);
    });

    //Акции
    Route::get('/actions/test', function () {

        $products = \Modules\Pars\Entities\Product::where('name', 'LIKE', '%JVC LT-42M450%')->first();//        $p=12;
        echo $products->name . '<br>';
        foreach ($products->actions()->get() as $action) {
            echo $action->name . '<br>';;
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


