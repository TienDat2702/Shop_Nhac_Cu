<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $brands = Brand::all();
        $banner = Banner::where('order', 1)->where('position', 1)->where('publish', 2)->first();
        $banner2 = Banner::where('order', 2)->where('position', 1)->where('publish', 2)->first();
        $banner3 = Banner::where('order', 3)->where('position', 1)->where('publish', 2)->first();
        $product_views = Product::GetProductPublish()->orderBy('view', 'desc')->take(2)->get();
        $product_price = Product::GetProductPublish()->orderBy('price_sale', 'asc')->take(8)->get();
        $products = Product::GetProductPublish()->orderBy('updated_at', 'desc')->paginate(8);

        $posts = Post::GetPostPublish()->limit(4)->get();

        return view('user.index', compact(
            'brands', 
            'product_views', 
            'product_price', 
            'products', 
            'banner', 
            'banner2', 
            'banner3',
            'posts'
        ));
    }
}
