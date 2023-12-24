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

    Route::apiResource('users', AdminController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('transactions', TransactionController::class);


    Route::post('users/login',['uses' => 'AdminController@login']);
    Route::get('idk/currentMonthStatistic',['uses' => 'TransactionController@currentMonthStatistic']);
    Route::get('check',['uses' => 'AdminController@checkToken']);
    Route::get('token',['uses' => 'AdminController@getToken']);
    Route::post('users/logout',['uses' => 'AdminController@logout']);
    Route::post('customers/bulk',['uses' => 'CustomerController@bulkStore']);


});