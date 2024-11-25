<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Mail\CongratulationsLoalty;
use App\Mail\OrderConfirmation;
use App\Mail\OrderSuccess;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\LoyaltyLevel;
use App\Models\ShowroomProduct;
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

    public function loyatal_level($total){
        $customer = Auth::guard('customer')->user();
        $loyaltyAmount = $customer->loyaltyLevel->discount_rate * $total;
        return $loyaltyAmount; //tính tiền giảm giá thành viên

    }

    public function checkout(Request $request){
        if ($request->has('resultCode')) {
            if ($request->input('resultCode') == 0) {
                return redirect()->route('checkout.completed');
            }
        }
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
        // giảm giá thành viên
        $loyaltyAmount = $this->loyatal_level($subtotal);

        return view('user.checkout',compact('customer', 'products', 'subtotal', 'discounts','discountAmount','loyaltyAmount'));

    }
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    private function momo_payment($order_id){

        // include "../common/helper.php";
        $order = Order::find($order_id);
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Đơn hàng không tồn tại.');
        }
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua ATM MoMo";
        $amount = (int) round($order->total);
        $orderId = $order->id;
        $redirectUrl = route('checkout');
        $ipnUrl = route('checkout');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM"; // thanh toán với ATM
        // $requestType = "captureWallet"; // thanh toán mã QR
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature);
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
        return redirect()->to($jsonResult['payUrl']);

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
                $order->status = 'Chờ xử lý';
                $order->save();

                // Xóa session
                // session()->forget('carts');
                Mail::to($order->email)->send(new OrderSuccess($order));
                toastr()->success('Thanh toán thành công');
                return redirect()->route('checkout.completed'); // Chuyển hướng đến trang hoàn tất
            }
        } else {
            toastr()->error('Thanh toán thất bại');
            return redirect()->route('checkout.failed'); // Chuyển hướng đến trang lỗi nếu cần
        }
    }

    public function onlineCheckout(CheckoutRequest $request)
{
    $method = $request->input('checkout_payment_method');
    $carts = session()->get('carts', []);
    $products = Product::GetProductPublish()->whereIn('id', array_column($carts, 'id'))->get();

    // Calculate total cart value
    $subtotal = $this->calculateTotal($carts, $products);
    $discountAmount = $this->applyDiscount($subtotal); // Apply discount
    $loyaltyAmount = $this->loyatal_level($subtotal); // Apply loyalty discount
    $total = $subtotal - $discountAmount - $loyaltyAmount; // Final total after discounts
    $token = Str::random(40); // Generate confirmation token

    // Retrieve nearest showroom info from request (if any)
    $nearestShowrooms = json_decode($request->input('nearest_showrooms'), true);

    if ($nearestShowrooms) {
        // Sort showroom by proximity (closest to farthest)
        usort($nearestShowrooms, function ($a, $b) {
            return $a['distance'] <=> $b['distance']; // Compare distance
        });
        $nearestShowroom = $nearestShowrooms[0]; // Get the closest showroom
    }

    // Prepare order data
    $data = [
        'customer_id' => Auth::guard('customer')->user()->id,
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'address' => $request->input('address'),
        'status' => 'Chưa xác nhận', // Status: Pending
        'payment_method' => $method,
        'customer_note' => $request->input('customer_note'),
        'total' => $total,
        'token' => $token,
    ];

    // Create the order
    $order = Order::create($data);

    if ($order) {
        session(['order' => $order->id]); // Store order ID in session

        foreach ($products as $product) {
            // Prepare order detail data
            $detail = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $carts[$product->id]['quantity'],
                'price' => $product->price_sale ? $product->price_sale : $product->price,
            ];
            OrderDetail::create($detail);

            // Decrease stock in the nearest showroom
            $remainingQuantity = $carts[$product->id]['quantity'];

            // Check in the nearest showroom first
            $showroomProduct = ShowroomProduct::where('showroom_id', $nearestShowroom['id'])
                ->where('product_id', $product->id)
                ->first();

            if ($showroomProduct && $showroomProduct->stock >= $remainingQuantity) {
                // Decrement stock in nearest showroom
                ShowroomProduct::where('showroom_id', $nearestShowroom['id'])
                    ->where('product_id', $product->id)
                    ->decrement('stock', $remainingQuantity);

                $remainingQuantity = 0; // All required stock has been deducted
            } else {
                // If the nearest showroom doesn't have enough stock, check other showrooms with publish = 4
                $showroomProductToTransfer = ShowroomProduct::where('product_id', $product->id)
                    ->whereHas('showroom', function ($query) {
                        $query->where('publish', 4); // Showrooms with publish = 4
                    })
                    ->first();

                if ($showroomProductToTransfer && $showroomProductToTransfer->stock >= $remainingQuantity) {
                    // Decrease stock in the old showroom
                    ShowroomProduct::where('showroom_id', $showroomProductToTransfer->showroom_id)
                        ->where('product_id', $product->id)
                        ->decrement('stock', $remainingQuantity);

                    // Transfer product to the nearest showroom
                    ShowroomProduct::updateOrCreate(
                        ['showroom_id' => $nearestShowroom['id'], 'product_id' => $product->id],
                        ['stock' => 0] // Add stock to nearest showroom
                    );

                    $remainingQuantity = 0; // All required stock has been transferred
                }
            }

            // If there is still remaining stock to be deducted after checking all showrooms
            if ($remainingQuantity > 0) {
                toastr()->error("Sản phẩm {$product->name} không đủ số lượng trong các showroom.");
                return redirect()->back();
            }
        }

        // Process payment methods
        if ($method == 'Thanh toán khi nhận hàng') {
            // Handle COD payment
        } elseif ($method == 'Thanh toán VNPAY') {
            return $this->vnpay_payment($order->id);
        } elseif ($method == 'Thanh toán MoMo') {
            return $this->momo_payment($order->id);
        }

        // Clear the cart
        session()->forget('carts');

        // Send order confirmation email
        Mail::to($order->email)->send(new OrderConfirmation($order, $token));

        toastr()->success('Thanh toán thành công');
        return redirect()->route('checkout.completed');
    } else {
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
        }else{
             // giảm giá thành viên
            $loyaltyAmount = $this->loyatal_level($order->total);
            $discountAmount = $this->applyDiscount($order->total);
            // Lấy chi tiết sản phẩm trong đơn hàng (quan hệ đã được thiết lập)
            $orderDetails = $order->orderDetails;
            // Xóa order_id khỏi session để tránh hiển thị lại đơn hàng này
            session()->forget('carts');
            $discount = Discount::where('id',session('discount_code'))->first();
            if ($discount) {
                $discount->use_count += 1;
                $discount->save();
            }
            $customer = Customer::firstWhere('id', Auth::guard('customer')->user()->id); // Lấy thông tin khách hàng

            $order_total = $customer->orders; // Lấy tất cả các đơn hàng của khách hàng
            $order_total_price = 0; // Biến để lưu tổng giá trị đơn hàng

            // Tính tổng giá trị đơn hàng
            foreach ($order_total as $item) {
                $order_total_price += $item->total; // Cộng dồn tổng giá trị đơn hàng
            }

            // Lấy tất cả các mức loyalty level (giả định rằng có trường `threshold` trong bảng loyalty levels)
            $loyaltyLevels = LoyaltyLevel::orderBy('order_total_price', 'asc')->get(); // Lấy danh sách các cấp độ theo thứ tự tăng dần

            if ($loyaltyLevels) {

                // Lưu giá trị loyalty_level_id trước khi cập nhật
                $oldLoyaltyLevelId = $customer->loyalty_level_id;
                // dd($oldLoyaltyLevelId);
                // Kiểm tra từng mức loyalty level
                foreach ($loyaltyLevels as $loyaltyLevel) {
                    if ($order_total_price > $loyaltyLevel->order_total_price && $customer->loyalty_level_id != 4) {

                        // Nếu tổng giá trị đơn hàng lớn hơn mức threshold hiện tại, cập nhật loyalty_level_id
                        $level = $loyaltyLevel->id;
                        $customer->loyalty_level_id = $level; // Cập nhật cấp độ thành viên của khách hàng
                        $loyalty = $loyaltyLevels->where('id', $level)->first();
                        // gửi email nếu loyalty_level_id thay đổi
                        if ($oldLoyaltyLevelId < $customer->loyalty_level_id) {
                            Mail::to($customer->email)->send(new CongratulationsLoalty($loyalty,$customer));
                        }
                    }
                }
            }

            // Lưu thay đổi vào cơ sở dữ liệu
            $customer->save();

            session()->forget('discount_code');
            session()->forget('order');

            // Trả về view với dữ liệu đơn hàng và chi tiết sản phẩm
            return view('user.order_completed', compact('order', 'orderDetails','discountAmount', 'loyaltyAmount'));
        }

    }

    public function verify($token)
    {
        $order = Order::where('token', $token)->first();

        if ($order) {
            $order->token = null;
            $order->status = 'Chờ xử lý';
            $order->save();
            toastr()->success('Xác nhận thành công');
            return redirect()->route('home.index');
        }else{
            toastr()->error('Xác nhận không thành công');
            return redirect()->route('home.index');
        }

    }
}
