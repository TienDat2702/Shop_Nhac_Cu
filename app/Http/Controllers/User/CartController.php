<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = session()->get('carts', []); 
        
        $total = 0;

        foreach($carts as $cart){
            $total += $cart['price'] * $cart['quantity'];
        }

        return view('user.cart', compact('carts', 'total')); // Trả về view giỏ hàng với dữ liệu giỏ hàng
    }

    public function add(Request $request)
    {
        $product_id = $request->input('product_id'); // Lấy ID sản phẩm từ request
        $carts = session()->get('carts', []); // Lấy giỏ hàng từ session
        $product = Product::find($product_id); // Tìm sản phẩm trong cơ sở dữ liệu
// Kiểm tra nếu sản phẩm không tồn tại
if (!$product) {
    toastr()->error('Sản phẩm không tồn tại.');
    return redirect()->back(); // Quay lại trang trước đó
}
        // Kiểm tra và lấy số lượng từ request, mặc định là 1
        $quantity = $request->input('quantity', 1);
        $price = ($product->price_sale > 0) ? $product->price_sale : $product->price;
        $subtotal = number_format($product->price * $quantity, 0, '.', ',') . ' VNĐ';
        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
        if (isset($carts[$product_id])) {
            // Tăng số lượng nếu sản phẩm đã có trong giỏ hàng
            $carts[$product_id]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $carts[$product_id] = [
                'id' => $product_id,
                'quantity' => $quantity,
                'name' => $product->name,
                'price' => $price,
                'image' => $product->image,
                'subtotal' => $subtotal
            ];
        }
        // Lưu giỏ hàng vào session
        session()->put('carts', $carts);
        toastr()->success('Thêm giỏ hàng thành công.'); // Hiển thị thông báo thành công
        return redirect()->back(); // Quay lại trang trước đó
    }

    public function update(Request $request){
        $quantities = $request->input('quantity'); // Lấy tất cả số lượng từ request
        $carts = session()->get('carts', []); // Lấy giỏ hàng từ session
    
        foreach ($quantities as $product_id => $quantity) {
            if (isset($carts[$product_id])) {
                // Cập nhật số lượng cho sản phẩm tương ứng
                $carts[$product_id]['quantity'] = $quantity;
                $product = Product::find($product_id);
                $subtotal = number_format($product->price * $quantity, 0, '.', ',') . ' VNĐ';
                $carts[$product_id]['subtotal'] = $subtotal; // Cập nhật subtotal
            }
        }
    
        session()->put('carts', $carts); // Lưu giỏ hàng vào session
    
        return redirect()->back(); // Quay lại trang trước đó
    }

    
    public function remove($id)
    {
        $carts = session()->get('carts', []);
    
        // Kiểm tra nếu sản phẩm có tồn tại trong giỏ hàng
        if (isset($carts[$id])) {
            // Xóa sản phẩm khỏi giỏ hàng
            unset($carts[$id]);
    
            // Lưu lại giỏ hàng sau khi xóa
            session()->put('carts', $carts);
    
            toastr()->success('Xóa thành công.');
        } else {
            toastr()->error('Sản phẩm không tồn tại trong giỏ hàng.');
        }
    
        // Quay lại trang trước đó
        return redirect()->back();
    }
    
}
