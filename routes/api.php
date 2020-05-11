<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//only -> solo se utilizaran index y show
Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);

//except -> todos los metodos menos create y edit
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);

Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);

Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);

Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);

Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);

