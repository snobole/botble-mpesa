<?php

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
use Snobole\Mpesa\Http\Controllers\C2BController;
use Snobole\Mpesa\Http\Controllers\STKPushController;

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
Route::group(['prefix' => 'api/payment', 'as' => 'api.mpesa.', 'namespace' => 'Snobole\Mpesa\Http\Controllers'], function () {
//    Route::group(['prefix' => 'c2b', 'as' => 'c2b.'], function () {
//        Route::post('register', [C2BController::class, 'register'])->name('register');
//        Route::post('simulate', [C2BController::class, 'simulate'])->name('simulate');
//        Route::post('confirm/{confirmation_key}', [C2BController::class, 'confirmTrx'])->name('confirm');
//        Route::post('validate/{validation_key}', [C2BController::class, 'validateTrx'])->name('validate');
//    });

    Route::group(['prefix' => 'stk-push', 'as' => 'stk-push.'], function () {
        Route::post('simulate', 'STKPushController@simulate')->name('simulate');
        Route::post('confirm/{confirmation_key}', 'STKPushController@confirm')->name('confirm');
    });
});
