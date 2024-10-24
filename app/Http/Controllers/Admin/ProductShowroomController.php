<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShowroomProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductShowroomController extends Controller
{
    public function index($showroomId)
    {
        // Truy vấn toàn bộ sản phẩm và stock từ showroom_products
        $product = ShowroomProduct::with('product') // Load thông tin từ bảng products
            ->where('showroom_id', $showroomId) // Điều kiện showroom_id
            ->get();

        // Trả về view với dữ liệu đã truy vấn
        return view('admin.Product_showroom.index', compact('product'));
    }
    public function updateProductInShowroom(Request $request)
    {
        // Validate yêu cầu, bao gồm kiểm tra product_id, showroom_id và stock
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'showroom_id' => 'required|exists:showrooms,id',
            'stock' => 'required|integer|min:0', // Xác thực stock phải là số nguyên >= 0
        ]);

        // Kiểm tra xem sản phẩm có trong showroom không
        $showroomProduct = DB::table('showroom_products')
            ->where('showroom_id', $request->showroom_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($showroomProduct) {
            // Nếu có, cập nhật stock
            DB::table('showroom_products')
                ->where('showroom_id', $request->showroom_id)
                ->where('product_id', $request->product_id)
                ->update(['stock' => $request->stock]);

            toastr()->success('Cập nhật số lượng sản phẩm thành công!');
        } else {
            toastr()->warning('Sản phẩm không tồn tại trong showroom này.');
        }

        return redirect()->back();
    }


public function removeProductFromShowroom(Request $request)
{
    // Validate yêu cầu, bao gồm kiểm tra product_id và showroom_id
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'showroom_id' => 'required|exists:showrooms,id',
    ]);

    // Tìm bản ghi showroom_product theo showroom_id và product_id
    $showroomProduct = DB::table('showroom_products')
        ->where('showroom_id', $request->showroom_id)
        ->where('product_id', $request->product_id)
        ->first();

    // Kiểm tra xem sản phẩm có trong showroom không
    if ($showroomProduct) {
        // Nếu có, xóa sản phẩm khỏi showroom
        DB::table('showroom_products')
            ->where('showroom_id', $request->showroom_id)
            ->where('product_id', $request->product_id)
            ->delete();
        
        toastr()->success('Sản phẩm đã được xóa khỏi showroom thành công!');
    } else {
        toastr()->warning('Sản phẩm không tồn tại trong showroom này.');
    }

    return redirect()->back();
}




}
