<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $brands = Brand::all();
        $product_views = Product::GetProductPublish()->orderBy('view', 'desc')->take(2)->get();
        $product_price = Product::GetProductPublish()->orderBy('price_sale', 'asc')->take(8)->get();
        $products = Product::GetProductPublish()->paginate(8);
        return view('user.index', compact('brands', 'product_views', 'product_price', 'products'));
    }
}
