<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Ajax\AjaxDashboardController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\Admin\BannerController;
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
Route::get('/login', [UserController::class, 'login'])->name('user.login');
Route::get('/register', [UserController::class, 'register'])->name('user.register');
Route::get('/cart', [OrderController::class, 'index'])->name('cart.index');

// AJAX
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');


    // POST CATEGORY
    Route::prefix('post')->group(function () {
        
        Route::get('category', [PostCategoryController::class,'index'])->name('postCatagory.index');

        Route::get('category/deleted', [PostCategoryController::class,'deleted'])->name('postCatagory.deleted');
        Route::get('category/search/{config}', [PostCategoryController::class,'search'])->name('postCatagory.search');


        Route::get('category/search', [PostCategoryController::class,'search'])->name('postCatagory.search');

        Route::get('category/create', [PostCategoryController::class,'create'])->name('postCatagory.create');
        Route::post('category/store', [PostCategoryController::class,'store'])->name('postCatagory.store');
        Route::get('category/edit/{slug}', [PostCategoryController::class,'edit'])->name('postCatagory.edit');
        Route::post('category/update/{slug}', [PostCategoryController::class,'update'])->name('postCatagory.update');
        Route::delete('category/destroy/{id}', [PostCategoryController::class,'destroy'])->name('postCatagory.destroy');
        Route::get('category/restore/{id}', [PostCategoryController::class,'restore'])->name('postCatagory.restore');
        Route::delete('category/forceDelete/{id}', [PostCategoryController::class,'forceDelete'])->name('postCatagory.forceDelete');
    });

    // POST
    Route::prefix('post')->group(function () {
        
        Route::get('/', [PostController::class,'index'])->name('post.index');
        Route::get('/test', [PostController::class,'test'])->name('post.test');
        Route::get('deleted', [PostController::class,'deleted'])->name('post.deleted');
        Route::get('search/{config}', [PostController::class,'search'])->name('post.search');
        Route::get('create', [PostController::class,'create'])->name('post.create');
        Route::post('store', [PostController::class,'store'])->name('post.store');
        Route::get('edit/{slug}', [PostController::class,'edit'])->name('post.edit');
        Route::post('update/{slug}', [PostController::class,'update'])->name('post.update');
        Route::delete('destroy/{id}', [PostController::class,'destroy'])->name('post.destroy');
        Route::get('restore/{id}', [PostController::class,'restore'])->name('post.restore');
        Route::delete('forceDelete/{id}', [PostController::class,'forceDelete'])->name('post.forceDelete');
    });
    Route::post('uploadImage', [UploadCKImageController::class, 'upload'])->name('ckeditor.upload');
    //Showroom
    Route::prefix('showroom')->group(function () {
        Route::get('create', [ShowroomController::class, 'create'])->name('showroom.create'); // Route mới
        Route::post('store', [ShowroomController::class, 'store'])->name('showroom.store'); // Route mới
        Route::get('category', [ShowroomController::class,'index'])->name('showroomcategory.index');
        Route::get('category/deleted', [ShowroomController::class, 'deleted'])->name('showroomcategory.deleted');
        Route::get('edit/{id}', [ShowroomController::class, 'edit'])->name('showroom.edit'); // Route để sửa
        Route::put('{id}', [ShowroomController::class, 'update'])->name('showroom.update'); // Route để cập nhật
        Route::post('toggle-publish/{id}', [ShowroomController::class, 'togglePublish'])->name('showroom.togglePublish');
        Route::post('showroom/{id}/restore', [ShowroomController::class, 'restore'])->name('showroom.restore');
Route::delete('showroom/{id}/force-delete', [ShowroomController::class, 'forceDelete'])->name('showroom.forceDelete');
Route::delete('showroom/{id}', [ShowroomController::class, 'destroy'])->name('showroom.destroy');

    });

