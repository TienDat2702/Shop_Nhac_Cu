<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Mail\OrderSuccess;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class CheckoutController extends Controller
{
     // Hàm tính tổng tiền giỏ hàng
     private function calculateTotal($carts, $products)
     {
         $total = 0;
         foreach ($carts as $cart) {
             $product = $products->firstWhere('id', $cart['id']);
             if ($product) {
                 $price = $product->price_sale ?? $product->price;
                 $total += $price * $cart['quantity'];
             }
         }
         return $total;
    }
    //Kiểm tra và tính giảm giá
    private function applyDiscount($total)
    {
        $discountAmount = 0;

        if (session()->has('discount_code')) {
            $discountCode = session('discount_code');
            $discount = Discount::GetDiscount()->where('id', $discountCode)->first();
            // Kiểm tra nếu mã giảm giá vẫn hợp lệ
            if ($discount) {
                $discountRate = $discount->discount_rate;
                $discountAmount = ($total * $discountRate) / 100;
                if ($discountAmount > $discount->max_value) {
                    $discountAmount = $discount->max_value;
                }
            } else {
                // Nếu mã giảm giá không hợp lệ, xoá session
                session()->forget('discount_code');
            }
        }

        return $discountAmount;
    }

    public function checkout(){

        $customer = Auth::guard('customer')->user();
        $carts = session()->get('carts', []);
        if (empty($carts)) {
            toastr()->warning('Hiện không có đơn hàng');
            return redirect()->route('home.index');
        }
        $subtotal = 0; // tổng phụ
        $discounts = Discount::get();
        $products = Product::GetProductPublish()->whereIn('id', array_column($carts, 'id'))->get();

        $subtotal = $this->calculateTotal($carts, $products);
        $discountAmount = $this->applyDiscount($subtotal);
    
        return view('user.checkout',compact('customer', 'products', 'subtotal', 'discounts','discountAmount'));
        
    }

    private function vnpay_payment($order_id){
        $order = Order::find($order_id);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "ZZHWIOWM";//Mã website tại VNPAY 
        $vnp_HashSecret = "1RE0AU691TE6Y5PFE1MU1C301CMUVWU1"; //Chuỗi bí mật
        
        $vnp_TxnRef = $order_id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        
        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        // Redirect to payment URL
        if (isset($_POST['redirect'])) {
            // Chuyển hướng đến VNPay
            header('Location: ' . $vnp_Url);
            exit(); // Dừng thực thi để chuyển hướng
        } else {
            // Nếu không redirect, trả về URL
            echo json_encode(['code' => '00', 'message' => 'success', 'data' => $vnp_Url]);
        }
    }

    public function vnpay_return(Request $request) {
        $vnp_TransactionStatus = $request->input('vnp_TransactionStatus');
        $vnp_TxnRef = $request->input('vnp_TxnRef'); // Mã đơn hàng
    
        // Kiểm tra trạng thái giao dịch
        if ($vnp_TransactionStatus === '00') {
            $order = Order::where('id', $vnp_TxnRef)->first();
    
            if ($order) {
                session(['order' => $order->id]); // lưu vào session
                // Cập nhật trạng thái đơn hàng
                $order->status = 'Đã thanh toán';
                $order->save();
    
                // Xóa session
                session()->forget('carts');
                Mail::to($order->email)->send(new OrderSuccess($order));
                toastr()->success('Thanh toán thành công');
                return redirect()->route('checkout.completed'); // Chuyển hướng đến trang hoàn tất
            }
        } else {
            toastr()->error('Thanh toán thất bại');
            return redirect()->route('checkout.failed'); // Chuyển hướng đến trang lỗi nếu cần
        }
    }

    public function onlineCheckout(Request $request) {
        $method = $request->input('checkout_payment_method');
        $carts = session()->get('carts', []);
        $products = Product::GetProductPublish()->whereIn('id', array_column($carts, 'id'))->get();
        
        $subtotal = $this->calculateTotal($carts, $products); // Tính tổng tiền giỏ hàng
        $discountAmount = $this->applyDiscount($subtotal); // Tính giảm giá
        $total = $subtotal - $discountAmount; // Tính tổng số tiền sau khi giảm giá
        $token = Str::random(40); // tạo token xác nhận
        $data = [
            'customer_id' => Auth::guard('customer')->user()->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'status' => 'Chưa thanh toán',
            'payment_method' => $method,
            'customer_note' => $request->input('customer_note'),
            'total' => $total,
            'token' => $token,
        ];
        $order = Order::create($data);
        if ($order) {
            session(['order' => $order->id]); // lưu vào session
            foreach($products as $product){
                $detail = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $carts[$product->id]['quantity'],
                    'price' => $product->price_sale ? $product->price_sale : $product->price,
                ];
                OrderDetail::create($detail);
            }
            session()->forget('carts');
            //nếu có method là VNPAY thì chuyển đến VNPAY
            if ($method == 'Thanh toán VNPAY') {
                return $this->vnpay_payment($order->id);
            }

            Mail::to($order->email)->send(new OrderConfirmation($order, $token));
            toastr()->success('Thanh toán thành công');
            return redirect()->route('checkout.completed');
        }else{
            toastr()->error('Thanh toán thất bại');
            return redirect()->back();
        }
        
    }
    
    public function order_completed(){
        // Lấy order_id từ session
        $order_id = session('order');
        // Tìm đơn hàng theo order_id
        $order = Order::find($order_id);
        if(!$order){
            toastr()->success('Cám ơn bạn <3');
            return redirect()->route('home.index');
        }
        $discountAmount = $this->applyDiscount($order->total);
        // Lấy chi tiết sản phẩm trong đơn hàng (quan hệ đã được thiết lập)
        $orderDetails = $order->orderDetails;

        // Xóa order_id khỏi session để tránh hiển thị lại đơn hàng này
        session()->forget('discount_code');
        session()->forget('order');

        // Trả về view với dữ liệu đơn hàng và chi tiết sản phẩm
        return view('user.order_completed', compact('order', 'orderDetails','discountAmount'));
    }

    public function verify($token)
    {
        $order = Order::where('token', $token)->first();

        if ($order) {
            $order->token = null;
            $order->status = 'Đã xác nhận';
            $order->save();
            toastr()->success('Xác nhận thành công');
            return redirect()->route('home.index');
        }else{
            toastr()->error('Xác nhận không thành công');
            return redirect()->route('home.index');
        }

    }
}
