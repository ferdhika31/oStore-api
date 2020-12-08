<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\OrderController;

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

Route::prefix('v1')->group( function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::get('products/{id}/stock/history', [ProductController::class, 'historyStock']);
    Route::post('products/{id}/stock/add', [ProductController::class, 'addStock']);
    Route::post('products/{id}/stock/reduce', [ProductController::class, 'reduceStock']);
    Route::post('products/{id}/stock/update', [ProductController::class, 'updateStock']);
    Route::resource('products', ProductController::class)->except(['destroy', 'create', 'edit']);

    Route::resource('orders', OrderController::class)->middleware('auth:api')->except(['destroy', 'create', 'edit']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
