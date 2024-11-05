<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShowroomProduct;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductShowroomController extends Controller
{
public function getProductsByPublishedShowroom()
{
    // Tìm showroom có publish là 4
    $showroom = Showroom::where('publish', 4)->first();
    
    // Kiểm tra xem showroom có tồn tại không
    if (!$showroom) {
        // Nếu không tìm thấy showroom, trả về thông báo lỗi
        return redirect()->back()->withErrors(['Không có showroom nào được publish!']);
    }

    // Truy vấn toàn bộ sản phẩm và stock từ showroom_products
    $product = ShowroomProduct::with('product') // Load thông tin từ bảng products
        ->where('showroom_id', $showroom->id) // Điều kiện showroom_id
        ->get();

        return view('admin.Kho_Product.index', compact('showroom', 'product'));
}


    public function index($showroomId)
{
    // Truy vấn showroom theo showroomId
    $showroom = Showroom::find($showroomId);
    if (!$showroom) {
        // Nếu không tìm thấy showroom, có thể trả về thông báo lỗi hoặc chuyển hướng
        return redirect()->back()->withErrors(['Showroom không tồn tại!']);
    }

    // Truy vấn toàn bộ sản phẩm và stock từ showroom_products
    $product = ShowroomProduct::with('product') // Load thông tin từ bảng products
        ->where('showroom_id', $showroomId) // Điều kiện showroom_id
        ->get();

    // Trả về view với dữ liệu đã truy vấn
    return view('admin.Product_showroom.index', compact('product', 'showroom'));
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


    public function transferAllProductsFromShowroom(Request $request, $showroomId)
{
    // Tìm showroom hiện tại bằng ID
    $currentShowroom = Showroom::find($showroomId);
    if (!$currentShowroom) {
        toastr()->error('Showroom không tồn tại!');
        return redirect()->back();
    }

    // Tìm showroom có publish = 4
    $targetShowroom = Showroom::where('publish', 4)->first();
    if (!$targetShowroom) {
        toastr()->warning('Không tìm thấy showroom có trạng thái publish là 4.');
        return redirect()->back();
    }

    // Lấy tất cả sản phẩm trong showroom hiện tại
    $showroomProducts = DB::table('showroom_products')
        ->where('showroom_id', $currentShowroom->id)
        ->get();

    // Kiểm tra xem có sản phẩm nào không
    if ($showroomProducts->isEmpty()) {
        toastr()->warning('Showroom này không có sản phẩm nào để chuyển.');
        return redirect()->back();
    }

    // Chuyển từng sản phẩm sang showroom có publish = 4
    foreach ($showroomProducts as $showroomProduct) {
        // Cộng stock vào showroom có publish = 4
        DB::table('showroom_products')->updateOrInsert(
            [
                'showroom_id' => $targetShowroom->id,
                'product_id' => $showroomProduct->product_id,
            ],
            [
                'stock' => DB::raw('stock + ' . $showroomProduct->stock) // Cộng dồn số lượng hàng tồn
            ]
        );
    }

    // Xóa tất cả sản phẩm trong showroom hiện tại
    DB::table('showroom_products')->where('showroom_id', $currentShowroom->id)->delete();

    toastr()->success('Tất cả sản phẩm đã được chuyển sang showroom có publish là 4 thành công!');
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
            // Tìm showroom có publish = 4
            $targetShowroom = DB::table('showrooms')->where('publish', 4)->first();
    
            // Kiểm tra xem showroom mục tiêu có tồn tại không
            if ($targetShowroom) {
                // Cộng stock vào showroom có publish = 4
                DB::table('showroom_products')
                    ->updateOrInsert(
                        ['showroom_id' => $targetShowroom->id, 'product_id' => $request->product_id],
                        ['stock' => DB::raw('stock + ' . $showroomProduct->stock)]
                    );
    
                // Xóa sản phẩm khỏi showroom hiện tại
                DB::table('showroom_products')
                    ->where('showroom_id', $request->showroom_id)
                    ->where('product_id', $request->product_id)
                    ->delete();
    
                toastr()->success('Sản phẩm đã được chuyển đến showroom có publish là 4 thành công!');
            } else {
                toastr()->warning('Không tìm thấy showroom có trạng thái publish là 4.');
            }
        } else {
            toastr()->warning('Sản phẩm không tồn tại trong showroom này.');
        }
    
        return redirect()->back();
    }



}
