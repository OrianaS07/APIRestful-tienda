<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);

Route::resource('products', ProductController::class);

Route::resource('transactions', TransactionController::class);

Route::resource('users', UserController::class);


