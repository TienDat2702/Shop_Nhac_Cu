<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Import model Product
use App\Models\ProductCategory; // Import model ProductCategory
use App\Models\Brand; // Import model Brand
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request
        $searchTerm = $request->input('search-keyword');

        // Tìm kiếm sản phẩm theo tên hoặc mô tả và phân trang
        $products = Product::where('name', 'LIKE', "%{$searchTerm}%")
            ->paginate(10); // Điều chỉnh số lượng sản phẩm trên mỗi trang

        // Lấy danh sách danh mục sản phẩm
        $productCategories = ProductCategory::all();

        // Lấy danh sách thương hiệu
        $brands = Brand::all(); // Fetch all brands
        $customer = Auth::guard('customer')->user();
        $product_favourite = '';
        if ( $customer) {
            $product_favourite = $customer->favourites->pluck('id','product_id')->toArray();
        }
       
        
        // Trả về view với dữ liệu tìm kiếm
        return view('user.search', compact('products', 'product_favourite', 'productCategories', 'brands', 'searchTerm'));
    }
}
