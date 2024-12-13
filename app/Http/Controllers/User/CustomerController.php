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
use App\Models\CustomerResetToken;
use App\Models\LoyaltyLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
        $route = 'home.index';
        // Lấy route từ request (giá trị từ input hidden)
        $route = $request->input('last_route', $route);
        $slug = $request->input('last_slug', null); // Lấy slug nếu có
        if (Auth::guard('customer')->attempt($credentials)) {
            // Nếu có slug, chuyển hướng về trang chi tiết sản phẩm
            if ($slug !== 'undefined' && $slug !== null) {
                return redirect()->route('product.detail', ['slug' => $slug])->with('success', 'Đăng nhập thành công');
            }
            $customer = Customer::where('id', Auth::guard('customer')->user()->id)->where('publish', 2)->first();
            if (!$customer) {
                Auth::guard('customer')->logout();
                toastr()->error('Bạn đã bị chặn không thể đăng nhập');
                return redirect()->route('customer.login');
            }else{
                toastr()->success('Đăng nhập thành công');
                return redirect()->route($route);
            }   
        } else {
            return redirect()->route('customer.login')->withInput()->with('error', 'Email hoặc Mật khẩu không chính xác');
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
      
        // $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    public function profile(){
        $customer = Auth::guard('customer')->user(); // Lấy thông tin người dùng hiện tại
        $loyalty = $customer->loyaltyLevel;
        $total_order = 0;
        $orders = Order::where('customer_id', $customer->id)->orderBy('id','DESC')->get();
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
        'email' => 'required|email|exists:customers,email',
    ], [
        'email.required' => 'Vui lòng nhập email',
        'email.exists' => 'Email không tồn tại trong hệ thống',
    ]);

    $customer = Customer::where('email', $req->email)->first();
    $token = Str::random(40);


    CustomerResetToken::where('email', $req->email)->delete();

    $tokenData = [
        'email' => $req->email,
        'token' => $token,
    ];

    if (CustomerResetToken::create($tokenData)) {
        $resetLink = route('customer.reset_password', ['token' => $token]);

        // Gửi email
        Mail::to($customer->email)->send(new ForgotPassword($customer, $resetLink));
        return redirect()->route('customer.login')->with('success', 'Đã gửi email khôi phục mật khẩu.');
    }

    return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
}


public function reset_password($token)
{
    // Tìm token trong cơ sở dữ liệu
    $tokenData = CustomerResetToken::where('token', $token)->first();

    // Kiểm tra token có tồn tại hay không
    if (!$tokenData) {
        return redirect()->route('customer.login')->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
    }

    $customer = Customer::where('email', $tokenData->email)->first();

    if (!$customer) {
        return redirect()->route('customer.login')->with('error', 'Không tìm thấy khách hàng.');
    }

    // Truyền token vào view reset password
    return view('user.reset_password', ['token' => $token]);
}


    public function check_reset_password(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirm-password' => 'required|same:password',
        ], [
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu ít nhất 8 ký tự',
            'confirm-password.required' => 'Bạn chưa nhập lại mật khẩu',
            'confirm-password.same' => 'Mật khẩu không trùng khớp',
        ]);

        $tokenData = CustomerResetToken::where('token', $token)->first();
        $customer = $tokenData->customer ?? null;

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Không tìm thấy khách hàng.');
        }

        $customer->password = Hash::make($request->password);

        if ($customer->save()) {
            $tokenData->delete(); // Xóa token sau khi đặt lại mật khẩu thành công
            return redirect()->route('customer.login')->with('success', 'Cập nhật mật khẩu thành công. Vui lòng đăng nhập');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
    }
    public function update_profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('user.update_profile', compact('customer'));
    }

    public function check_update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Vui lòng nhập tên đầy đủ.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'phone.required' => 'Vui lòng nhp số điện thoại.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
        ]);

        $customer = Auth::guard('customer')->user();

        
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        $customer->save();

        return redirect()->route('customer.update.profile')->with('success', 'Cập nhật thông tin thành công.');
    }

    public function change_password()
    {
        return view('user.userChangePassword');
    }

    public function check_change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Bạn chưa nhập mật khẩu hiện tại',
            'new_password.required' => 'Bạn chưa nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'new_password.confirmed' => 'Mật khẩu mới không trùng khớp',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            session()->flash('error', 'Mật khẩu hiện tại không chính xác.');
            return redirect()->back();
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        session()->flash('success', 'Thay đổi mật khẩu thành công.');
        return redirect()->route('customer.update.profile');
    }
    public function customerOrder()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->orderBy('id','DESC')->paginate(10);
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
                ['text' => 'Đã duyệt', 'status' => 'Đã duyệt'],
                ['text' => 'Đang chuẩn bị hàng', 'status' => 'Đang chuẩn bị hàng'],
                ['text' => 'Đang giao', 'status' => 'Đang giao'],
                ['text' => 'Đã giao', 'status' => 'Đã giao'],
            ];
            
            // Chỉ mục trạng thái của các bước
            $statusOrder = [
                'Đơn hàng đã đặt' => 1,
                'Chưa xác nhận' => 2,
                'Chờ xử lý' => 3,
                'Đã duyệt' => 4,
                'Đang chuẩn bị hàng' => 5,
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
        return ($status / $totalSteps) * 95; // Tính tỷ lệ phần trăm tiến trình
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
