<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->route('user.login')->with('error', 'Email hoặc Mật khẩu không chính xác');
    }

    public function postRegister(RegisterRequest $request)
    {
        try {
            Customer::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
            ]);

            return redirect()->route('user.login')->with('success', 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }

    public function profile(){
        return view('user.profile');
    }

    public function userOrder(){
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->get();
        return view('user.userOrder', compact('orders'));
    }

    //
}
