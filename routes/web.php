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

Route::resource('commission', 'CommissionController');
Route::resource('ibo', 'IboController');
Route::resource('package', 'PackageController');
Route::resource('packagetype', 'PackageTypeController');
Route::resource('productamount', 'ProductAmountController');
Route::resource('product', 'ProductController');
Route::resource('productpurchase', 'ProductPurchaseController');
Route::resource('rankinglion', 'RankingLionController');
Route::resource('rebate', 'RebateController');
Route::resource('genealogy', 'GenealogyController');
