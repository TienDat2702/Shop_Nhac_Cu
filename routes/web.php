<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminPostCategoryController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminProductCategoryController;
use App\Http\Controllers\Admin\UploadCKImageController;
use App\Http\Controllers\Ajax\AjaxDashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\User\UserShowroomController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductShowroomController;
use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\LoyaltyController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CustomerAuth;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Middleware\CheckCustomer;

Route::middleware(CheckCustomer::class)->group(function () {
    
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/brand/{slug}', [HomeController::class, 'brand'])->name('brands.index');
    Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
    Route::get('/shop/category/{slug}', [ProductController::class, 'category'])->name('shop.category');
    Route::get('/product/{slug}', [ProductController::class, 'product_details'])->name('product.detail');
    Route::get('/showrooms/map', [UserShowroomController::class, 'showMap'])->name('showrooms.map');
    // CHÍNH SÁCH
    Route::get('/showrooms/chinh_sach_bao_hanh', [HomeController::class, 'page_chinh_sach_bao_hanh'])->name('page.chinh_sach_bao_hanh');
    Route::get('/showrooms/chinh_sach_giao_hang', [HomeController::class, 'page_chinh_sach_giao_hang'])->name('page.chinh_sach_giao_hang');
    // ABOUT
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    // CONTACT
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/contact/post', [HomeController::class, 'postContact'])->name('contact.post');

    // GIỎ HÀNG
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update/quantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
        Route::post('/discount', [CartController::class, 'discount'])->name('cart.discount');
        Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });



    // TIN TỨC
    Route::prefix('post')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('post.page');
        Route::get('/detail/{slug}', [PostController::class, 'detail'])->name('post.detail');
        Route::get('/category/{slug}', [PostController::class, 'category'])->name('post.category');
        Route::get('/category/all/{slug}', [PostController::class, 'categoryAll'])->name('post.category.all');
    });

    // user;
    Route::get('/login', [CustomerController::class, 'login'])->name('customer.login');
    Route::post('/do-login', [CustomerController::class, 'dologin'])->name('customer.dologin');
    Route::get('/verify-account/{email}', [CustomerController::class, 'verify'])->name('customer.verify');
    Route::get('/register', [CustomerController::class, 'register'])->name('customer.register');
    Route::post('/register', [CustomerController::class, 'check_register'])->name('customer.check_register');

    Route::middleware(CustomerAuth::class)->group(function () {
        Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
        Route::post('/profile', [CustomerController::class, 'check_profile'])->name('customer.chek_profile');
        Route::get('/update-profile',[CustomerController::class,'update_profile'])->name('customer.update.profile');
        Route::post('/update-profile',[CustomerController::class,'check_update_profile'])->name('customer.check_update');
        Route::get('/change-password', [CustomerController::class, 'change_password'])->name('customer.change_password');
        Route::post('/change-password', [CustomerController::class, 'check_change_password'])->name('customer.check_change_password');

        // THANH TOÁN
        Route::prefix('checkout')->group(function () {
            Route::get('/', [CheckoutController::class, 'checkout'])->name('checkout');
            Route::post('/online', [CheckoutController::class, 'onlineCheckout'])->name('checkout.online');
            Route::get('vnpay-return', [CheckoutController::class, 'vnpay_return'])->name('vnpay.return');
            Route::get('momo-return', [CheckoutController::class, 'momo_return'])->name('momo.return');
            Route::get('/completed', [CheckoutController::class, 'order_completed'])->name('checkout.completed');
            Route::get('/verify/{token}', [CheckoutController::class, 'verify'])->name('checkout.verify');
            Route::get('/showrooms/nearest', [ShowroomController::class, 'findNearestShowroom'])->name('checkout.showroom');
        });

        //comment
        Route::post('/product/{proId}/comment', [ProductController::class, 'post_comment'])->name('product.comment');

        Route::get('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
        Route::get('/orders', [CustomerController::class, 'customerOrder'])->name('customer.orders');
        Route::get('/orders/history', [CustomerController::class, 'customerOrderHistory'])->name('customer.orders.history');
        Route::post('/orders/cancel', [CustomerController::class, 'customerOrderCancel'])->name('customer.orders.cancel');
        Route::get('/orders/{id}', [CustomerController::class, 'customerOrderDetail'])->name('customer.orders.detail');


        Route::prefix('wishlist')->group(function () {
            Route::get('/', [FavouriteController::class, 'index'])->name('wishlist.index'); // Xem wishlist
            Route::post('/add/{id}', [FavouriteController::class, 'add'])->name('wishlist.add'); // Thêm sản phẩm vào wishlist
            Route::delete('/remove/{id}', [FavouriteController::class, 'remove'])->name('wishlist.remove'); // Xóa sản phẩm khỏi wishlist
        });
    });


    Route::get('/forgot', [CustomerController::class, 'forgot'])->name('customer.forgot');
    Route::post('/forgot', [CustomerController::class, 'check_forgot'])->name('customer.check_forgot');
    Route::get('/reset-password/{token}', [CustomerController::class, 'reset_password'])->name('customer.reset_password');
    Route::post('/reset-password/{token}', [CustomerController::class, 'check_reset_password'])->name('customer.check_reset_password');
});

Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'check_login'])->name('admin.check_login');
Route::get('/admin/forgot', [AdminController::class, 'forgot'])->name('admin.forgot');
Route::post('/admin/forgot', [AdminController::class, 'check_forgot'])->name('admin.check_forgot');
Route::get('/admin/reset-password/{token}', [AdminController::class, 'reset_password'])->name('admin.reset_password');
Route::post('/admin/reset-password/{token}', [AdminController::class, 'check_reset_password'])->name('admin.check_reset_password');
// AJAX CHANGE PUBLISH
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');

// ADMIN
Route::middleware(['AdminAuth'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    //Discount
    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index'])->name('discount.index');
        Route::get('/create', [DiscountController::class, 'create'])->name('discount.create');
        Route::post('/', [DiscountController::class, 'store'])->name('discount.store');
        Route::get('/{id}/edit', [DiscountController::class, 'edit'])->name('discount.edit');
        Route::put('/{id}', [DiscountController::class, 'update'])->name('discount.update');
        Route::delete('/{id}', [DiscountController::class, 'destroy'])->name('discount.destroy');
        Route::post('/{id}/restore', [DiscountController::class, 'restore'])->name('discount.restore');
        Route::delete('discount/forceDelete/{id}', [AdminPostCategoryController::class, 'forceDelete'])->name('discount.forceDelete');
    });
    // ORDER
    Route::prefix('order')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('order.index');
        Route::get('/pending', [AdminOrderController::class, 'OrderPending'])->name('order.pending');
        Route::get('/orders/search', [AdminOrderController::class, 'search'])->name('order.search');
        Route::get('/detail/{id}', [AdminOrderController::class, 'show'])->name('order.show');
        Route::put('/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('order.updateStatus');
    });
    // POST CATEGORY
    Route::prefix('post')->group(function () {

        Route::get('category', [AdminPostCategoryController::class, 'index'])->name('postCategory.index');
        Route::get('category/deleted', [AdminPostCategoryController::class, 'deleted'])->name('postCategory.deleted');
        Route::get('category/search/{config}', [AdminPostCategoryController::class, 'search'])->name('postCategory.search');
        Route::get('category/create', [AdminPostCategoryController::class, 'create'])->name('postCategory.create');
        Route::post('category/store', [AdminPostCategoryController::class, 'store'])->name('postCategory.store');
        Route::get('category/edit/{slug}', [AdminPostCategoryController::class, 'edit'])->name('postCategory.edit');
        Route::post('category/update/{slug}', [AdminPostCategoryController::class, 'update'])->name('postCategory.update');
        Route::delete('category/destroy/{id}', [AdminPostCategoryController::class, 'destroy'])->name('postCategory.destroy');
        Route::get('category/restore/{id}', [AdminPostCategoryController::class, 'restore'])->name('postCategory.restore');
        Route::delete('category/forceDelete/{id}', [AdminPostCategoryController::class, 'forceDelete'])->name('postCategory.forceDelete');
    });

    // POST
    Route::prefix('post')->group(function () {

        Route::get('/', [AdminPostController::class, 'index'])->name('post.index');
        Route::get('deleted', [AdminPostController::class, 'deleted'])->name('post.deleted');
        Route::get('search/{config}', [AdminPostController::class, 'search'])->name('post.search');
        Route::get('create', [AdminPostController::class, 'create'])->name('post.create');
        Route::post('store', [AdminPostController::class, 'store'])->name('post.store');
        Route::get('edit/{slug}', [AdminPostController::class, 'edit'])->name('post.edit');
        Route::post('update/{slug}', [AdminPostController::class, 'update'])->name('post.update');
        Route::delete('destroy/{id}', [AdminPostController::class, 'destroy'])->name('post.destroy');
        Route::get('restore/{id}', [AdminPostController::class, 'restore'])->name('post.restore');
        Route::delete('forceDelete/{id}', [AdminPostController::class, 'forceDelete'])->name('post.forceDelete');
    });

    //User
    Route::prefix('user')->group(function () {
        Route::get('/', [AdminAccountController::class, 'index'])->name('user.index');
        Route::get('/deleted', [AdminAccountController::class, 'deleted'])->name('user.deleted');
        Route::get('/search/{config}', [AdminAccountController::class, 'search'])->name('user.search');
        Route::get('/create', [AdminAccountController::class, 'create'])->name('user.create');
        Route::post('/store', [AdminAccountController::class, 'store'])->name('user.store');
        Route::get('/edit/{id}', [AdminAccountController::class, 'edit'])->name('user.edit');
        Route::post('/update/{id}', [AdminAccountController::class, 'update'])->name('user.update');
        Route::delete('/destroy/{id}', [AdminAccountController::class, 'destroy'])->name('user.destroy');
        Route::get('/restore/{id}', [AdminAccountController::class, 'restore'])->name('user.restore');
        Route::delete('/forceDelete/{id}', [AdminAccountController::class, 'forceDelete'])->name('user.forceDelete');
    });
    //CUSTOMER

    Route::prefix('customer')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('customer.index');
        Route::get('/deleted', [AdminCustomerController::class, 'deleted'])->name('customer.deleted');
        Route::get('/search/{config}', [AdminCustomerController::class, 'search'])->name('customer.search');
        Route::get('/create', [AdminCustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [AdminCustomerController::class, 'store'])->name('customer.store');
        Route::get('/edit/{id}', [AdminCustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/update/{id}', [AdminCustomerController::class, 'update'])->name('customer.update');
        Route::delete('/destroy/{id}', [AdminCustomerController::class, 'destroy'])->name('customer.destroy');
        Route::get('/restore/{id}', [AdminCustomerController::class, 'restore'])->name('customer.restore');
        Route::delete('/forceDelete/{id}', [AdminCustomerController::class, 'forceDelete'])->name('customer.forceDelete');
    });

    //SHOWROOM
    Route::prefix('showroom')->group(function () {
        Route::get('create', [ShowroomController::class, 'create'])->name('showroom.create'); // Route mới
        Route::post('store', [ShowroomController::class, 'store'])->name('showroom.store'); // Route mới
        Route::get('category', [ShowroomController::class, 'index'])->name('showroomcategory.index');
        Route::get('category/deleted', [ShowroomController::class, 'deleted'])->name('showroomcategory.deleted');
        Route::get('edit/{id}', [ShowroomController::class, 'edit'])->name('showroom.edit'); // Route để sửa
        Route::put('{id}', [ShowroomController::class, 'update'])->name('showroom.update'); // Route để cập nhật
        Route::get('showroom/{id}/restore', [ShowroomController::class, 'restore'])->name('showroom.restore');
        Route::delete('showroom/{id}/force-delete', [ShowroomController::class, 'forceDelete'])->name('showroom.forceDelete');
        Route::delete('showroom/{id}', [ShowroomController::class, 'destroy'])->name('showroom.destroy');
        Route::get('/showrooms/{showroomId}/add-product', [ShowroomController::class, 'showAddProductForm'])->name('showroom.showAddProductForm');
        Route::post('/showrooms/products', [ShowroomController::class, 'addProductToShowroom'])->name('showroom.addProduct');
        Route::get('/api/showrooms', [ShowroomController::class, 'searchShowrooms'])->name('Search.showroom');
    });

    // PRODUCT SHOWROOM
    Route::prefix('Product_showroom')->group(function () {
        Route::get('category/{showroomId}/products', [ProductShowroomController::class, 'index'])->name('Productshowroom.index');
        Route::get('kho/products', [ProductShowroomController::class, 'getProductsByPublishedShowroom'])->name('Kho.index');
        Route::post('/update-product', [ProductShowroomController::class, 'updateProductInShowroom'])->name('Productshowroom.update');
        Route::post('/admin/transfer-product/{showroomId}', [ProductShowroomController::class, 'transferProductFromShowroom'])->name('Productshowroom.remove');
        Route::post('/{id}/transfer-all', [ProductShowroomController::class, 'transferAllProductsFromShowroom'])->name('showroom.transferAll');
        Route::post('/transfer-product', [ProductShowroomController::class, 'transfer'])->name('transfer.product');
        Route::get('/showtransfer', [ProductShowroomController::class, 'showLogs'])->name('transfer.show');
    });

    // BANNER
    Route::prefix('banner')->group(function () {
        Route::get('category', [BannerController::class, 'index'])->name('banner.index'); // Route mới
        Route::post('toggle-publish/{id}', [BannerController::class, 'togglePublish'])->name('banner.togglePublish');
        Route::get('edit/{id}', [BannerController::class, 'edit'])->name('banner.edit'); // Route để sửa
        Route::put('{id}', [BannerController::class, 'update'])->name('banner.update'); // Route để cập nhật
        Route::get('banner/{id}/restore', [BannerController::class, 'restore'])->name('banner.restore');
        Route::delete('banner/{id}/force-delete', [BannerController::class, 'forceDelete'])->name('banner.forceDelete');
        Route::delete('banner/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');
        Route::get('create', [BannerController::class, 'create'])->name('banner.create'); // Route mới
        Route::post('store', [BannerController::class, 'store'])->name('banner.store');
    });
    // UPLOAD IMAGE CKEDITOR
    Route::post('/admin/upload-ck-image', [UploadCKImageController::class, 'upload'])->name('ckeditor.upload');
    // UPLOAD IMAGE
    Route::post('uploadImage', [UploadCKImageController::class, 'upload'])->name('ckeditor.upload');

    // PRODUCT CATEGORY
    Route::prefix('/product')->group(function () {
        Route::get('/category', [AdminProductCategoryController::class, 'index'])->name('productCategory.index');
        Route::get('/category/deleted', [AdminProductCategoryController::class, 'deleted'])->name('productCategory.deleted');
        Route::get('/category/create', [AdminProductCategoryController::class, 'create'])->name('productCategory.create');
        Route::post('/category/store', [AdminProductCategoryController::class, 'store'])->name('productCategory.store');
        Route::get('/category/edit/{slug}', [AdminProductCategoryController::class, 'edit'])->name('productCategory.edit');
        Route::post('/category/update/{slug}', [AdminProductCategoryController::class, 'update'])->name('productCategory.update');
        Route::delete('/category/destroy/{id}', [AdminProductCategoryController::class, 'destroy'])->name('productCategory.destroy');
        Route::get('/category/restore/{id}', [AdminProductCategoryController::class, 'restore'])->name('productCategory.restore');
        Route::delete('/category/forceDelete/{id}', [AdminProductCategoryController::class, 'forceDelete'])->name('productCategory.forceDelete');
    });

    // PRODUCT
    Route::prefix('product')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('product.index');
        Route::get('/deleted', [AdminProductController::class, 'deleted'])->name('product.deleted');
        Route::get('/create', [AdminProductController::class, 'create'])->name('product.create');
        Route::post('/store', [AdminProductController::class, 'store'])->name('product.store');
        Route::get('/edit/{slug}', [AdminProductController::class, 'edit'])->name('product.edit');
        Route::post('/update/{slug}', [AdminProductController::class, 'update'])->name('product.update');
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
        Route::get('edit/{slug}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('update/{slug}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
        Route::get('restore/{id}', [BrandController::class, 'restore'])->name('brand.restore');
        Route::delete('forceDelete/{id}', [BrandController::class, 'forceDelete'])->name('brand.forceDelete');
    });

    // CẤP ĐỘ THÀNH VIÊN
    Route::prefix('loyalty')->group(function () {
        Route::get('/', [LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::post('store', [LoyaltyController::class, 'update'])->name('loyalty.update');
    });

    // Bình luận
    Route::prefix('comment')->group(function () {
        Route::get('/', [AdminCommentController::class, 'index'])->name('comment.index');
        Route::delete('delete/{id}', [AdminCommentController::class, 'delete'])->name('comment.delete');
    });

});

//Search
Route::get('/search', [SearchController::class, 'index'])->name('search');
