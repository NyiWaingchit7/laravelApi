<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group(['prefix'=>'brand'],function($router){
    Route::controller(BrandController::class)->group(function(){
        Route::get('index','index');
        Route::get('index/{id}','detail');
        Route::post('create','store');
        Route::put('update/{id}','update_brand');
        Route::delete('delete/{id}','delete_brand');

    });
});

Route::group(['prefix'=>'category'],function($router){
    Route::controller(CategoryController::class)->group(function(){
        Route::get('index','index');
        Route::get('index/{id}','detail');
        Route::post('create','store');
        Route::post('update/{id}','update_category');
        Route::delete('delete/{id}','delete_category');
    });
});

Route::group(['prefix'=>'location'],function($router){
    Route::controller(LocationController::class)->group(function(){

        Route::post('create','store');
        Route::put('update/{id}','update_location');
        Route::delete('delete/{id}','delete_location');
    });
});

Route::group(['prefix'=>'product'],function($router){
    Route::controller(ProductController::class)->group(function(){
        Route::get('index','index');
        Route::get('index/{id}','detail');
        Route::post('create','store');
        Route::post('update/{id}','update_product');
        Route::delete('delete/{id}','delete_product');

    });
});

Route::group(['prefix'=>'order'],function($router){
    Route::controller(OrderController::class)->group(function(){
        Route::get('index','index');
        Route::get('index/{id}','detail');
        Route::post('create','store');
        Route::get('get_order_items/{id}','get_order_items');
        Route::get('get_user_orders/{id}','get_user_orders');
        Route::post('change_status/{id}','change_status');


    });
});
