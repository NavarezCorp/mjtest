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
Route::resource('user', 'UserController');

Route::get('commissionsummaryreport/all/{search?}', 'CommissionSummaryReportController@get_all');
Route::get('commission/manualcompute/{id}', 'CommissionSummaryReportController@manual_compute');
Route::resource('commissionsummaryreport', 'CommissionSummaryReportController');

Route::get('waiting/{id}', 'CommissionSummaryReportController@process_waiting');
Route::get('matching/{id}', 'CommissionSummaryReportController@process_matching');
Route::get('automatching/{id}', 'CommissionSummaryReportController@process_auto_matching');

Route::resource('activationtype', 'ActivationTypeController');

Route::get('activationcode/get_activation_code', 'ActivationCodeController@get_activation_code');
Route::get('activationcode/check_activation_code', 'ActivationCodeController@check_activation_code');
Route::get('/activationcode/print_code/{type}', 'ActivationCodeController@print_code');
Route::resource('activationcode', 'ActivationCodeController');

Route::get('genealogy/get_genealogy', 'GenealogyController@get_genealogy');
Route::resource('genealogy', 'GenealogyController');

Route::get('productcode/get_product_code', 'ProductCodeController@get_product_code');
Route::get('productcode/check_product_code', 'ProductCodeController@check_product_code');
Route::get('productcode/all', 'ProductCodeController@get_all_product_codes');
Route::get('productcode/print/{type}', 'ProductCodeController@print_product_codes');
Route::resource('productcode', 'ProductCodeController');