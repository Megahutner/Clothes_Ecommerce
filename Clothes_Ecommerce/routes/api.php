<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'],function(){

    // Laravel's premapped CRUD APIs
    Route::apiResource('users', AdminController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('transactions', TransactionController::class);


    // Custom APIs
    Route::get('rootAccount',['uses' => 'AdminController@rootAccount']);
    Route::post('users/login',['uses' => 'AdminController@login']);
    Route::post('users/logout',['uses' => 'AdminController@logout']);
    Route::post('users/reset',['uses' => 'AdminController@resetPassword']);


    Route::post('customers/login',['uses' => 'CustomerController@login']);
    Route::post('customers/verify',['uses' => 'CustomerController@verify']);
    Route::post('customers/sendReset',['uses' => 'CustomerController@sendResetMail']);
    Route::post('customers/reset',['uses' => 'CustomerController@resetPassword']);
    Route::post('customers/logout',['uses' => 'CustomerController@logout']);
    Route::post('customers/bulk',['uses' => 'CustomerController@bulkStore']);


    Route::get('customerTransaction', ['uses'=>'TransactionController@customerTransaction']);
    Route::post('transactions/addToCart', ['uses'=>'TransactionController@addToCart']);
    Route::post("products/uploadImage",['uses'=>'ProductController@uploadImage']);
    Route::post('transactions/removeFromCart', ['uses'=>'TransactionController@removeFromCart']);
    Route::post('transactions/makeTransaction', ['uses'=>'TransactionController@makeTransaction']);
    Route::post('transactions/toCheckOut', ['uses'=>'TransactionController@toCheckOut']);
    Route::post('transactions/endTransaction', ['uses'=>'TransactionController@endTransaction']);





    Route::get('basic/general',['uses' => 'BasicController@generalStatistics']);
    Route::get('basic/latestCustomers',['uses' => 'BasicController@latestCustomerReg']);
    Route::get('basic/latestTransactions',['uses' => 'BasicController@latestTransactions']);
    Route::get('basic/currentMonthStatistic',['uses' => 'BasicController@currentMonthStatistic']);
    Route::get('basic/getEnumCategories',['uses' => 'BasicController@getEnumCategories']);

    
    Route::get('check',['uses' => 'AdminController@checkToken']);

    Route::get('cart',['uses' => 'TransactionController@getCustomerCart']);     

    Route::get('token',['uses' => 'AdminController@getToken']);

    Route::get('show',['uses' => 'ProductController@customerShow']);




});