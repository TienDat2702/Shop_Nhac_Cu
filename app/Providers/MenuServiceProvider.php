<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Favourite;
use App\Models\PostCategory;
use App\Models\ProductCategory;
use App\Models\Showroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       // Gửi danh mục đến tất cả các view
        View::composer('user.*', function ($view) {
        $categorie_parent_post = PostCategory::GetAllByPublish()->where('parent_id', '0')->get();
        $categories_post = PostCategory::GetAllByPublish()->get();
        $categorie_parent_product = ProductCategory::GetAllByPublish()->where('parent_id', 0)->get();
        $categorie_product = ProductCategory::GetAllByPublish()->get();
        $list_showroom = Showroom::GetAllByPublish()->get();
        $favourite = [];
        if (Auth::guard('customer')->user()) {
            $favourite = Favourite::where('customer_id', Auth::guard('customer')->user()->id)->get();
        }
       
        $view->with([
            'categorie_parent_post' => $categorie_parent_post,
            'categories_post' => $categories_post,
            'categorie_parent_product' => $categorie_parent_product,
            'categorie_product' => $categorie_product,
            'favourite' => $favourite,
            'list_showroom' => $list_showroom,
        ]);
    });
    }
}
