<?php

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::resource('categories', CategoryController::class)->except('create','edit');
Route::resource('products', ProductController::class)->only('index','show');
Route::resource('transactions', TransactionController::class);

Route::post('register', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'authenticate']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::resource('users', UserController::class)->except('create','edit');
    Route::get('user', [UserController::class, 'getAuthenticatedUser']);
});

Route::resource('buyers', BuyerController::class)->only('index','show');
Route::resource('sellers', SellerController::class)->only('index','show');

