<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\ForgotPassword;
use App\Mail\VerifyAccount;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User; // Đổi từ Customer sang User
use App\Models\CustomerResetToken;
use App\Models\LoyaltyLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function login()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home.index');
        }
        return view('user.login');
    }

    public function dologin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $customer = Customer::where('email', $request->email)->first();

        if ($customer && is_null($customer->email_verified_at)) {
            return redirect()->route('customer.login')->with('error', 'Vui lòng xác minh email của bạn trước khi đăng nhập.');
        }

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công');
        } else {
            \Log::info('Đăng nhập thất bại với thông tin: ', $credentials);
            return redirect()->route('customer.login')->with('error', 'Email hoặc Mật khẩu không chính xác');
        }
    }

    public function register()
    {
        return view('user.register');
    }

    public function check_register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'phone');
        $data['password'] = bcrypt($request->password);
        $loyoty_level = LoyaltyLevel::where('id',1)->first();
        if ($loyoty_level) {
            $data['loyalty_level_id'] = 1;
        }
        
        // $data['role_id'] = 1; // Đặt role_id = 1 cho khách hàng

        if ($acc = Customer::create($data)) {
            Mail::to($acc->email)->send(new VerifyAccount($acc));
            return redirect()->route('customer.login')->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email của bạn.');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
    }

    public function verify($email)
    {
        $acc = Customer::where('email', $email)->whereNull('email_verified_at')->firstOrFail();
        $acc->email_verified_at = now();
        $acc->save();

        return redirect()->route('customer.login')->with('success', 'Xác minh thành công. Vui lòng đăng nhập');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    public function profile(){
        $customer = Auth::guard('customer')->user(); // Lấy thông tin người dùng hiện tại
        $loyalty = $customer->loyaltyLevel;
        $total_order = 0;
        $orders = Order::where('customer_id', $customer->id)->get();
        foreach($orders as $order){
            $total_order += $order->total;
        }
        return view('user.profile', compact('customer', 'loyalty','total_order'));
    }

    public function check_profile(UpdateProfileRequest $request)
    {
        $user = Auth::guard('customer')->user();

        if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác.']);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('customer.profile')->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }

    public function forgot()
    {
        return view('user.forgot');
    }

    public function check_forgot(Request $req)
    {
        $req->validate([
            'email' => 'required|exists:customers,email',
        ], [
            'email.required' => 'Bạn hãy nhập địa chỉ email',
            'email.exists' => 'Email này không tồn tại trong hệ thống'
        ]);

        $customer = Customer::where('email', $req->email)->first();
        $token = \Str::random(40);

        CustomerResetToken::where('email', $req->email)->delete();

        $tokenData = [
            'email' => $req->email,
            'token' => $token,
        ];

        if (CustomerResetToken::create($tokenData)) {
            Mail::to($req->email)->send(new ForgotPassword($customer, $token));
            return redirect()->route('customer.login')->with('success', 'Gửi mail thành công. Vui lòng kiểm tra email.');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
    }

    public function reset_password($token)
    {
        $tokenData = CustomerResetToken::checkToken($token);
        $customer = $tokenData->customer ?? null;

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Không tìm thấy khách hàng.');
        }

        return view('user.reset_password', compact('token'));
    }
    
    public function check_reset_password($token)
    {
        request()->validate(
            [
                'password' => 'required|min:8',
                'confirm-password' => 'required|same:password',
            ],
            [
                'password.required' => 'Bạn chưa nhập mật khẩu',
                'password.min' => 'Mật khẩu ít nhất 8 ký tự',
                'confirm-password.required' => 'Bạn chưa nhập lại mật khẩu',
                'confirm-password.same' => 'Mật khẩu không trùng khớp',
            ]
        );
    
        $tokenData = CustomerResetToken::checkToken($token);
        $customer = $tokenData->customer ?? null;
    
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Không tìm thấy khách hàng.');
        }
    
        // Cập nhật mật khẩu đã mã hóa
        $customer->password = Hash::make(request('password'));
    
        if ($customer->save()) {
            // Xóa token sau khi đổi mật khẩu thành công để bảo mật
            $tokenData->delete();
            return redirect()->route('customer.login')->with('success', 'Cập nhật mật khẩu thành công. Vui lòng đăng nhập');
        }
    
        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
    }
    

    public function customerOrder()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->get();
        return view('user.userOrder', compact('orders'));
    }

    public function customerOrderDetail($id)
    {
        $order = Order::find($id);
       
        if ($order) {
            $orderStatus = $order->status; // Trạng thái của đơn hàng
        
            // Các bước trạng thái và thời gian tương ứng
            $steps = [
                ['text' => 'Đơn hàng đã đặt', 'status' => 'Đơn hàng đã đặt'],
                ['text' => 'Xác nhận đơn hàng', 'status' => 'Chưa xác nhận'],
                ['text' => 'Chờ xử lý', 'status' => 'Chờ xử lý'],
                ['text' => 'Đã duyệt', 'status' => 'Duyệt'],
                ['text' => 'Đang giao', 'status' => 'Đang giao'],
                ['text' => 'Đã giao', 'status' => 'Đã giao'],
            ];
            
            // Chỉ mục trạng thái của các bước
            $statusOrder = [
                'Đơn hàng đã đặt' => 1,
                'Chưa xác nhận' => 2,
                'Chờ xử lý' => 4,
                'Duyệt' => 5,
                'Đang giao' => 6,
                'Đã giao' => 7,
            ];
        
            // Nếu trạng thái là "Đã thanh toán", loại bỏ bước "Chưa xác nhận" và các bước sau đó
            if ($orderStatus == 'Đã thanh toán') {
                $steps = array_filter($steps, function($step) {
                    return $step['status'] != 'Chưa xác nhận';
                });
        
                // Reset lại chỉ mục mảng để tránh lỗi khi duyệt mảng
                $steps = array_values($steps);
            }
        
            // Xử lý các trạng thái
            foreach ($steps as $index => &$step) {
                if ($step['status'] == $orderStatus) {
                    // Đánh dấu trạng thái hiện tại
                    $step['class'] = 'progress-step--current';
                } 
                elseif (isset($statusOrder[$orderStatus]) && $statusOrder[$orderStatus] > $statusOrder[$step['status']]) {
                    // Đánh dấu bước đã hoàn thành
                    $step['class'] = 'progress-step--completed';
                } else {
                    // Bước chưa hoàn thành
                    $step['class'] = '';
                }
            }
        
            // Tính toán tỷ lệ tiến trình
            $progressWidth = $this->getProgressWidth($orderStatus, $statusOrder);
        }
        else{
            abort(404);
        }
    
        return view('user.userOrderDetail', compact('order', 'steps', 'orderStatus', 'progressWidth'));
    }
    
    
    private function getProgressWidth($orderStatus, $statusOrder)
    {
        // Lấy giá trị tiến trình từ trạng thái hiện tại
        $status = $statusOrder[$orderStatus] ?? 0;
        $totalSteps = count($statusOrder);
        return ($status / $totalSteps) * 82; // Tính tỷ lệ phần trăm tiến trình
    }
    



    public function customerOrderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Đơn hàng không tồn tại.');
        }
        if ($order->status == 'Đã giao') {
            $order->status = "Đã nhận hàng";
            toastr()->success('Cảm ơn bạn đã xác nhận');
        }
        if ($order->status =='Chờ xử lý' || $order->status =='Chưa xác nhận') {
            $order->status = "Đã hủy";
            $order->delete();
            toastr()->error('Bạn đẫ hủy xin hãy đặt đơn hàng mới');
        }
        $order->save();

        return redirect()->route('customer.orders');
    }

    public function customerOrderHistory()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->paginate(10);
        return view('user.userOrderHistory', compact('orders'));
    }
}
