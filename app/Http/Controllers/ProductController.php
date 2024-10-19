<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('parent_id', 0)->get();
        $brands = Brand::all();
        $products = Product::paginate(9);

        return view('shop', compact('categories', 'brands', 'products'));
    }

    public function product_details()
    {
        return view('details');
    }
}
