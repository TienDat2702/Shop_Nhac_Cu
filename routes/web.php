<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Ajax\AjaxDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ProductController::class,'product_details'])->name('shop.product.details');


// user
Route::get('/login', [UserController::class, 'login'])->name('user.login');
Route::post('/do-login', [UserController::class, 'dologin'])->name('user.dologin');
Route::get('/register', [UserController::class, 'register'])->name('user.register');

Route::get('/cart', [OrderController::class, 'index'])->name('cart.index');

// AJAX
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');


    // POST CATEGORY
    Route::prefix('post')->group(function () {
        
        Route::get('category', [PostCategoryController::class,'index'])->name('postCatagory.index');
        Route::get('category/search', [PostCategoryController::class,'search'])->name('postCatagory.search');
        Route::get('category/create', [PostCategoryController::class,'create'])->name('postCatagory.create');
        Route::post('category/store', [PostCategoryController::class,'store'])->name('postCatagory.store');
        Route::get('category/edit/{id}', [PostCategoryController::class,'edit'])->name('postCatagory.edit');
        Route::post('category/update/{id}', [PostCategoryController::class,'update'])->name('postCatagory.update');
        Route::delete('category/destroy/{id}', [PostCategoryController::class,'destroy'])->name('postCatagory.destroy');
    });
    
});