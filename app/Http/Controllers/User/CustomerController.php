<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\ForgotPassword;
use App\Mail\VerifyAccount;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerResetToken;
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

        // Kiểm tra xem tài khoản đã xác minh chưa
        if ($customer && is_null($customer->email_verified_at)) {
            return redirect()->route('customer.login')->with('error', 'Vui lòng xác minh email của bạn trước khi đăng nhập.');
        }

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->route('customer.login')->with('error', 'Email hoặc Mật khẩu không chính xác');
    }


    public function register()
    {
        return view('user.register');
    }
    public function check_register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'phone');
        $data['password'] = bcrypt($request->password);
        if ($acc = Customer::create($data)) {
            Mail::to($acc->email)->send(new VerifyAccount(($acc)));
            return redirect()->route('customer.login')->with('success', 'đăng ký thành công. Vui lòng kiểm tra email');
        }
        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng hãy thử lại');
    }
    public function verify($email)
    {
        $acc = Customer::where('email', $email)->whereNull('email_verified_at')->firstOrFail();
        $acc->email_verified_at = now(); // Cập nhật bằng now() cho đúng định dạng ngày giờ
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
    public function profile()
    {
        $user = Auth::guard('customer')->user();
        return view('user.account-detail', compact('user'));
    }

    public function check_profile(UpdateProfileRequest $request)
    {
        // Lấy người dùng hiện tại
        $user = Auth::guard('customer')->user();

        // Kiểm tra mật khẩu cũ, nếu người dùng yêu cầu thay đổi mật khẩu
        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác.']);
            }
        }

        // Cập nhật thông tin cá nhân
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Kiểm tra và cập nhật mật khẩu mới nếu có
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        // Lưu lại các thay đổi
        $user->save();

        // Chuyển hướng người dùng về trang tài khoản và hiển thị thông báo thành công
        return redirect()->route('customer.profile')->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }


    public function change_password() {}
    public function forgot()
    {
        return view('user.forgot');
    }

    public function check_forgot(Request $req)
    {
        $req->validate([
            'email' => 'required|exists:customers',
        ], [
            'email.required' => 'Bạn hãy nhập địa chỉ email',
            'email.exists' => 'Email này không tồn tại'
        ]);

        $customer = Customer::where('email', $req->email)->first();
        $token = \Str::random(40);

        // Xóa token cũ nếu tồn tại
        CustomerResetToken::where('email', $req->email)->delete();

        // Tạo token mới
        $tokenData = [
            'email' => $req->email,
            'token' => $token,
        ];

        if (CustomerResetToken::create($tokenData)) {
            Mail::to($req->email)->send(new ForgotPassword($customer, $token));
            return redirect()->route('customer.login')->with('success', 'Gửi mail thành công. Vui lòng kiểm tra mail');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
    }





    public function reset_password($token)
    {
       
        $tokenData = CustomerResetToken::checkToken($token);
        $customer = $tokenData->customer;

       
        return view('user.reset_password', compact('token'));
    }
    public function check_reset_password($token) {
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
        $customer = $tokenData->customer;

        $data = [
            'password' => bcrypt(request(('password')))
        ];
        $check=$customer->update($data);
        if ($check){
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
        return view('user.userOrderDetail', compact('order'));
    }

    public function customerOrderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = "đã hủy";
        $order->save();
        return redirect()->route('customer.orders')->with('success', 'Đơn hàng đã được hủy thành công');
    }

    public function customerOrderHistory()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->paginate(10);

        return view('user.userOrderHistory', compact('orders'));
    }

    //
}
