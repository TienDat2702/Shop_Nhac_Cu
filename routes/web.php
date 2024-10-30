<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\DiscountController;
<<<<<<< Updated upstream
=======
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\UploadCKImageController;
>>>>>>> Stashed changes
use App\Http\Controllers\Ajax\AjaxDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Admin\DiscountController;



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
<<<<<<< Updated upstream
        
        Route::get('category', [PostCategoryController::class,'index'])->name('postCatagory.index');

        Route::get('category/search', [PostCategoryController::class,'search'])->name('postCatagory.search');
        Route::get('category/create', [PostCategoryController::class,'create'])->name('postCatagory.create');
        Route::post('category/store', [PostCategoryController::class,'store'])->name('postCatagory.store');
        Route::get('category/edit/{id}', [PostCategoryController::class,'edit'])->name('postCatagory.edit');
        Route::post('category/update/{id}', [PostCategoryController::class,'update'])->name('postCatagory.update');
        Route::delete('category/destroy/{id}', [PostCategoryController::class,'destroy'])->name('postCatagory.destroy');
    });
    
});

=======
        Route::get('category', [PostCategoryController::class,'index'])->name('postCategory.index');
        Route::get('category/deleted', [PostCategoryController::class,'deleted'])->name('postCategory.deleted');
        Route::get('category/search/{config}', [PostCategoryController::class,'search'])->name('postCategory.search');
        Route::get('category/create', [PostCategoryController::class,'create'])->name('postCategory.create');
        Route::post('category/store', [PostCategoryController::class,'store'])->name('postCategory.store');
        Route::get('category/edit/{slug}', [PostCategoryController::class,'edit'])->name('postCategory.edit');
        Route::post('category/update/{slug}', [PostCategoryController::class,'update'])->name('postCategory.update');
        Route::delete('category/destroy/{id}', [PostCategoryController::class,'destroy'])->name('postCategory.destroy');
        Route::get('category/restore/{id}', [PostCategoryController::class,'restore'])->name('postCategory.restore');
        Route::delete('category/forceDelete/{id}', [PostCategoryController::class,'forceDelete'])->name('postCategory.forceDelete');
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
    Route::post('/admin/upload-ck-image', [UploadCKImageController::class, 'upload'])->name('ckeditor.upload');
    // PRODUCT CATEGORY
    Route::prefix('product')->group(function () {
        Route::get('category', [ProductCategoryController::class, 'index'])->name('productCategory.index');
        Route::get('category/deleted', [ProductCategoryController::class, 'deleted'])->name('productCategory.deleted');
        Route::get('category/create', [ProductCategoryController::class, 'create'])->name('productCategory.create');
        Route::post('category/store', [ProductCategoryController::class, 'store'])->name('productCategory.store');
        Route::get('category/edit/{id}', [ProductCategoryController::class, 'edit'])->name('productCategory.edit');
        Route::post('category/update/{id}', [ProductCategoryController::class, 'update'])->name('productCategory.update');
        Route::delete('category/destroy/{id}', [ProductCategoryController::class, 'destroy'])->name('productCategory.destroy');
        Route::get('category/restore/{id}', [ProductCategoryController::class, 'restore'])->name('productCategory.restore');
        Route::delete('category/forceDelete/{id}', [ProductCategoryController::class, 'forceDelete'])->name('productCategory.forceDelete');
    });
    // PRODUCT
    Route::prefix('product')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('product.index');
        Route::get('/deleted', [AdminProductController::class, 'deleted'])->name('product.deleted');
        Route::get('/create', [AdminProductController::class, 'create'])->name('product.create');
        Route::post('/store', [AdminProductController::class, 'store'])->name('product.store');
        Route::get('/edit/{id}', [AdminProductController::class, 'edit'])->name('product.edit');
        Route::post('/update/{id}', [AdminProductController::class, 'update'])->name('product.update');
        Route::delete('/destroy/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/restore/{id}', [AdminProductController::class, 'restore'])->name('product.restore');
        Route::delete('/forceDelete/{id}', [AdminProductController::class, 'forceDelete'])->name('product.forceDelete');
    });
    // BRAND
    Route::prefix('brand')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('brand.index');
        Route::get('deleted', [BrandController::class, 'deleted'])->name('brand.deleted');
        Route::get('create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('store', [BrandController::class, 'store'])->name('brand.store');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('update/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
        Route::get('restore/{id}', [BrandController::class, 'restore'])->name('brand.restore');
        Route::delete('forceDelete/{id}', [BrandController::class, 'forceDelete'])->name('brand.forceDelete');
    });
<<<<<<< Updated upstream
    Route::prefix('order')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('order.index');
        Route::get('/pending', [AdminOrderController::class, 'OrderPending'])->name('order.pending');
        Route::get('/detail/{id}', [AdminOrderController::class, 'OrderDetail'])->name('order.detail');
    });
=======
>>>>>>> Stashed changes
    Route::prefix('voucher')->group(function () {
        Route::get('/', [DiscountController::class, 'index'])->name('admin.discounts.index');
        Route::get('/create', [DiscountController::class, 'create'])->name('admin.discounts.create');
        Route::post('/', [DiscountController::class, 'store'])->name('admin.discounts.store');
        Route::get('/{discount}/edit', [DiscountController::class, 'edit'])->name('admin.discounts.edit');
        Route::put('/{discount}', [DiscountController::class, 'update'])->name('admin.discounts.update');
        Route::delete('/{discount}', [DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
    });
<<<<<<< Updated upstream
});
=======
 });
>>>>>>> Stashed changes






>>>>>>> Stashed changes
