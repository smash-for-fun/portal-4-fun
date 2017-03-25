<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
    // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('group', 'Admin\GroupCrudController');
    CRUD::resource('page', 'Admin\PageCrudController');



});


//$router->group(['namespace' => 'Backpack\PageManager\app\Http\Controllers'], function ($router) {
//    // Admin Interface Routes
//    Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('backpack.base.route_prefix', 'admin')], function () {
//        // Backpack\PageManager routes
//        Route::get('page/create/{template}', 'Admin\PageCrudController@create');
//        Route::get('page/{id}/edit/{template}', 'Admin\PageCrudController@edit');
//
//        // This triggered an error before publishing the PageTemplates trait, when calling Route::controller();
//        // CRUD::resource('page', 'Admin\PageCrudController');
//
//        // So for PageCrudController all routes are explicitly defined:
//        Route::get('page/reorder', 'Admin\PageCrudController@reorder');
//        Route::get('page/reorder/{lang}', 'Admin\PageCrudController@reorder');
//        Route::post('page/reorder', 'Admin\PageCrudController@saveReorder');
//        Route::post('page/reorder/{lang}', 'Admin\PageCrudController@saveReorder');
//        Route::get('page/{id}/details', 'Admin\PageCrudController@showDetailsRow');
//        Route::get('page/{id}/translate/{lang}', 'Admin\PageCrudController@translateItem');
//        Route::resource('page', 'Admin\PageCrudController');
//    });
//});