<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\UploadCKImageController;
use App\Http\Controllers\Ajax\AjaxDashboardController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{product_slug}',[ProductController::class,'product_details'])->name('product.detail');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/quantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
    Route::post('/discount', [CartController::class, 'discount'])->name('cart.discount');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

});
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/online', [CheckoutController::class, 'onlineCheckout'])->name('checkout.online');
    Route::get('vnpay-return', [CheckoutController::class, 'vnpay_return'])->name('vnpay.return');
    Route::get('/completed', [CheckoutController::class, 'order_completed'])->name('checkout.completed');
    Route::get('/verify/{token}', [CheckoutController::class, 'verify'])->name('checkout.verify');
});
// user;
Route::get('/login', [CustomerController::class, 'login'])->name('customer.login');
Route::post('/do-login', [CustomerController::class, 'dologin'])->name('customer.dologin');
Route::get('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
Route::get('/orders', [CustomerController::class, 'customerOrder'])->name('customer.orders');

// Route::prefix('order')->group(function () {
//     Route::get('/', [AdminOrderController::class, 'index'])->name('order.index');
//     Route::get('/pending', [AdminOrderController::class, 'OrderPending'])->name('order.pending');
//     Route::get('/detail/{id}', [AdminOrderController::class, 'OrderDetail'])->name('order.detail');
// });

// AJAX
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::prefix('order')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('order.index');
        Route::get('/pending', [AdminOrderController::class, 'OrderPending'])->name('order.pending');
        Route::get('/detail/{id}', [AdminOrderController::class, 'show'])->name('order.show');
        Route::put('/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('order.updateStatus');
    });

    // POST CATEGORY
    Route::prefix('post')->group(function () {
        
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
    Route::prefix('order')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('order.index');
        Route::get('/pending', [AdminOrderController::class, 'OrderPending'])->name('order.pending');
        Route::get('/detail/{id}', [AdminOrderController::class, 'OrderDetail'])->name('order.detail');
    });
});
