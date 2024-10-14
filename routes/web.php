<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ProductController::class,'product_details'])->name('shop.product.details');
Route::get('/login', [UserController::class, 'login'])->name('user.login');
Route::get('/register', [UserController::class, 'register'])->name('user.register');

Route::get('/cart', [OrderController::class, 'index'])->name('cart.index');