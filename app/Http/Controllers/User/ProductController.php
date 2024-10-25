<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $products = Product::paginate(9);
        $categories = ProductCategory::where('parent_id', 0)->get();
        $brands = Brand::all();
        return view('user.shop', compact('categories', 'brands', 'products'));
    }

    public function product_details($slug)
    {
        $product = Product::where('slug',$slug)->first();
        $product->view += 1000;
        $product->save();
        $brand = Brand::find($product->brand_id);
        $product_related = Product::where('category_id', $product->category_id)->where('slug', '!=', $slug)->get();
        return view('user.details', compact('product', 'brand', 'product_related'));
    }
}