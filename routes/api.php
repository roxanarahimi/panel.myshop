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
    Route::get('/get/products/{id}', 'productByCat');
    Route::get('/get/product/{slug}', 'product');
    Route::get('/get/banners', 'banners');
    Route::get('/search', 'search');
    Route::get('/special/products', 'specialProducts');


});

Route::controller(App\Http\Controllers\UserController::class)->group(function () {


    Route::post('/mobile/otp',  'sendOtp');
    Route::post('/mobile/verify','verifyMobile');

    Route::get('/user/{id}', 'show');
    Route::post('/update/user', 'update');
    Route::post('/store/address', 'storeAddress');
    Route::post('/update/address', 'updateAddress');

});

Route::controller(App\Http\Controllers\ShopController::class)->group(function () {

    Route::get('/categories', 'categories');
    Route::get('/brands', 'brands');
    Route::get('/products', 'products');//where: categories, stock, off---- sort: new,sale,price
    Route::get('/product/{slug}', 'product');

    Route::get('/provinces', 'provinces');
    Route::get('/cities/{id}', 'cities');

    Route::post('/add/to/cart', 'addToCart');//user_id,p_id,quantity
    Route::post('/remove/from/cart', 'removeFromCart');
    Route::post('/update/cart', 'updateCart');//cart id
    Route::post('/empty/cart', 'emptyCart');//cart id

    Route::get('/order/{code}', 'showOrder');//cart id

    Route::post('/pay/cart', 'payCart');//user_id

    Route::post('/payment', 'payment');

    Route::post('/update/order', 'updateOrder');

});



