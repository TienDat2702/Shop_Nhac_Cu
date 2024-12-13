<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class APIProductController extends Controller
{
    public function getPrice(Request $request)
    {
        // Lấy tên sản phẩm từ query string
        $productName = $request->query('product_name');

        // Tìm sản phẩm theo tên
        $product = Product::where('name', $productName)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'name' => $product->name,
                'price' => $product->price,
            ], 200);
        }

        // Trả về thông báo nếu không tìm thấy  
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm.'
        ], 404);
    }
}
