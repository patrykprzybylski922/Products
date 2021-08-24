<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $products = \App\Models\Product::paginate(10);
    return view('welcome')->with('products', $products);
});

Route::get('/products', 'App\Http\Controllers\ProductsController@getProducts');
Route::delete('/products/destroy/{id}', 'App\Http\Controllers\ProductsController@destroy');
