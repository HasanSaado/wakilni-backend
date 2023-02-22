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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// user actions
Route::post('/user/register', 'App\Http\Controllers\AuthController@register');
Route::post('/user/login', 'App\Http\Controllers\AuthController@login');
Route::middleware('auth:api')->get('/user/logout', 'App\Http\Controllers\AuthController@logout');
Route::middleware('auth:api')->get('/user/refresh', 'App\Http\Controllers\AuthController@refresh');

// product actions
Route::middleware('auth:api')->post('/product', 'App\Http\Controllers\ProductController@index');
Route::middleware('auth:api')->post('/product/create', 'App\Http\Controllers\ProductController@create');
Route::middleware('auth:api')->delete('/product/delete/{product}', 'App\Http\Controllers\ProductController@delete');
Route::middleware('auth:api')->put('/product/update', 'App\Http\Controllers\ProductController@update');
Route::middleware('auth:api')->get('/product/{product}', 'App\Http\Controllers\ProductController@getProduct');
Route::middleware('auth:api')->get('/product', 'App\Http\Controllers\ProductController@getProducts');

// item actions
Route::middleware('auth:api')->post('/item', 'App\Http\Controllers\ItemController@index');
Route::middleware('auth:api')->post('/item/create', 'App\Http\Controllers\ItemController@create');
Route::middleware('auth:api')->delete('/item/delete/{item}', 'App\Http\Controllers\ItemController@delete');
Route::middleware('auth:api')->get('/item/{product}', 'App\Http\Controllers\ItemController@getItems');
Route::middleware('auth:api')->post('/item/update', 'App\Http\Controllers\ItemController@update');
