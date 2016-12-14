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

Route::get('/aboutus', function(){
    return view('about_us');
});

Route::get('/contactus', function(){
    return view('contact_us');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('commission', 'CommissionController');

Route::get('/ibosearch', 'IboController@ibo_search');
Route::get('/ibo/search', 'IboController@search');
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
Route::get('ibocommission/{id}', 'CommissionSummaryReportController@get_ibo_commission');
Route::get('iboindirect/{id}', 'CommissionSummaryReportController@get_ibo_indirect');
Route::get('indirectcommission500up/{search?}', 'CommissionSummaryReportController@get_indirect_500_up');
Route::get('allindirectcommission/{search?}', 'CommissionSummaryReportController@get_all_indirect');
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

Route::resource('bank', 'BankController');
Route::resource('maritalstatus', 'MaritalStatusController');
Route::resource('gender', 'GenderController');
Route::resource('country', 'CountryController');
Route::resource('city', 'CityController');
Route::resource('pickupcenter', 'PickupCenterController');