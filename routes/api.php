<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//Route::options('/{any}', function (Request $request) {
//    return response('', 200)
//        ->header('Access-Control-Allow-Origin', '*')//aa
//        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
//        ->header('Access-Control-Allow-Credentials', 'true');
//})->where('any', '.*');

Route::controller(App\Http\Controllers\ClientSideController::class)->group(function () {
    Route::get('/get/contents/{id}', 'contents');
    Route::get('/get/content/{slug}', 'content');
    Route::get('/get/banners', 'banners');
    Route::get('/search', 'search');


});

Route::controller(App\Http\Controllers\UserController::class)->group(function () {

    Route::post('/user/otp', 'otp');
    Route::post('/user/verify', 'verify');//=>login
    Route::post('/get/user', 'user');//=>info, orders, addresses,cart

    Route::post('/update/user', 'user');//=>mobile
    Route::post('/store/address', 'storeAddress');
    Route::post('/update/address', 'updateAddress');


});

Route::controller(App\Http\Controllers\ShopController::class)->group(function () {

    Route::get('/products', 'products');//where: categories, stock, off---- sort: new,sale,price
    Route::get('/product/{slug}', 'product');

    Route::post('/update/cart', 'updateCart');//user_id,p_id,quantity
    Route::post('/empty/cart', 'emptyCart');//user_id

    Route::post('/pay/cart', 'payCart');//user_id

    Route::post('/payment', 'payment');

    Route::post('/update/order', 'updateOrder');

});



