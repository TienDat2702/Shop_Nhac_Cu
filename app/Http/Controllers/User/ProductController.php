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
        $brands = $request->input('brands');
        $categories = $request->input('categories');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $query = Product::query();
    
        // Lọc theo thương hiệu
        if (!empty($brands)) {
            $query->whereIn('brand_id', $brands);
        }
    
        // Lọc theo danh mục
        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }
    
        // Lọc theo giá nếu có giá trị được gửi
        if (!is_null($minPrice) && !is_null($maxPrice)) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
    
        $products = $query->paginate(9);
        $allCategories = ProductCategory::where('parent_id', 0)->get();
        $allBrands = Brand::all();
    
        if ($request->ajax()) {
            return view('user.shop', compact('allCategories', 'allBrands', 'products'))->render();
        }
    
        return view('user.shop', compact('allCategories', 'allBrands', 'products'));
    }
    
    

    public function product_details($id)
    {
        $product = Product::find($id);
        $product->view += 1;
        $product->save();
        $brand = Brand::find($product->brand_id);
        $product_related = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->get();
        return view('user.details', compact('product', 'brand', 'product_related'));
    }
}
