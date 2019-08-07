<?php

use Illuminate\Http\Request;
use Modules\Skills\Http\Controllers;
use Modules\Skills\Http\Controllers\ArticleRelationshipController;


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
    Route::resource('languages', 'LanguageController', ['names' => [
        'store' => 'skills.languages.store',
        'index' => 'skills.languages.index',
        'create' => 'skills.languages.create',
        'destroy' => 'skills.languages.destroy',
        'update' => 'skills.languages.update',
        'show' => 'skills.languages.show',
        'edit' => 'skills.languages.edit',
    ]]);
    // меняем наименование ресурсных роутов по схеме префикс.ресурс.действие
    Route::resource('articles', 'ArticleController', ['names' => [
        'store' => 'skills.articles.store',
        'index' => 'skills.articles.index',
        'create' => 'skills.articles.create',
        'destroy' => 'skills.articles.destroy',
        'update' => 'skills.articles.update',
        'show' => 'skills.articles.show',
        'edit' => 'skills.articles.edit',
    ]]);

    Route::resource('autors', 'AutorController', ['names' => [
        'store' => 'skills.autors.store',
        'index' => 'skills.autors.index',
        'create' => 'skills.autors.create',
        'destroy' => 'skills.autors.destroy',
        'update' => 'skills.autors.update',
        'show' => 'skills.autors.show',
        'edit' => 'skills.autors.edit',
    ]]);

    Route::resource('comments', 'CommentController', ['names' => [
        'store' => 'skills.comments.store',
        'index' => 'skills.comments.index',
        'create' => 'skills.comments.create',
        'destroy' => 'skills.comments.destroy',
        'update' => 'skills.comments.update',
        'show' => 'skills.comments.show',
        'edit' => 'skills.comments.edit',
    ]]);

    Route::resource('languages', 'LanguageController', ['names' => [
        'store' => 'skills.languages.store',
        'index' => 'skills.languages.index',
        'create' => 'skills.languages.create',
        'destroy' => 'skills.languages.destroy',
        'update' => 'skills.languages.update',
        'show' => 'skills.languages.show',
        'edit' => 'skills.languages.edit',
    ]]);

    Route::get(
        'articles/{article}/relationships/autor',
        [
            'uses' => ArticleRelationshipController::class . '@autor',
            'as' => 'skills.articles.relationships.autor',
        ]
    );
    Route::get(
        'articles/{article}/autor',
        [
            'uses' => ArticleRelationshipController::class . '@autor',
            'as' => 'skills.articles.autor',
        ]
    );
    Route::get(
        'articles/{article}/relationships/comments',
        [
            'uses' => ArticleRelationshipController::class . '@comments',
            'as' => 'skills.articles.relationships.comments',
        ]
    );
    Route::get(
        'articles/{article}/comments',
        [
            'uses' => ArticleRelationshipController::class . '@comments',
            'as' => 'skills.articles.comments',
        ]
    );


});
