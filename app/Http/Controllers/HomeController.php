<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $brands = Brand::all();
        $product_views = Product::orderBy('view', 'desc')->take(2)->get();
        $product_price = Product::orderBy('price_sale', 'asc')->take(8)->get();
        $products = Product::orderBy('updated_at', 'desc')->paginate(8);
        return view('index', compact('brands', 'product_views', 'product_price', 'products'));
    }
}
