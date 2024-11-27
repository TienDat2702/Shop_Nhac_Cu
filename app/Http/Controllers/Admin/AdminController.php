<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Models\AdminResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;

class AdminController extends Controller
{
    public function login()
    {
        if (auth()->check() && auth()->user()) {
            return redirect()->route('dashboard.index');
        }

        return view('admin.login');
    }

    public function check_login(AuthRequest $request)
    {
        $data = $request->only('email', 'password');
        if (auth()->attempt($data)) {
            // Kiểm tra xem người dùng có vai trò là admin không
            // if (auth()->user()) {
            //     return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            // }
            $user = User::where('id', auth()->user()->id)->where('publish', 2)->first();
            if (!$user) {
                auth()->logout(); 
                return redirect()->route('admin.login')->with('error', 'Bạn đã bị chặn');
            }else{
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }
        }
        
        return redirect()->back()->withInput()->with('error', 'Tài khoản không chính xác. Vui lòng đăng nhập lại!');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công.');
    }

    public function forgot()
    {
        return view('admin.forgot');
    }

    public function check_forgot(Request $req)
{
    $req->validate([
        'email' => 'required|email|exists:users,email',
    ], [
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không hợp lệ',
        'email.exists' => 'Email không tồn tại trong hệ thống',
    ]);

    $user = User::where('email', $req->email)->first();
    $token = \Str::random(40);

    AdminResetToken::where('email', $req->email)->delete();

    $tokenData = [
        'email' => $req->email,
        'token' => $token,
    ];

    if (AdminResetToken::create($tokenData)) {
        $resetLink = route('admin.reset_password', ['token' => $token]);

        // Gửi email
        Mail::to($user->email)->send(new ForgotPassword($user, $resetLink));
        return redirect()->route('admin.login')->with('success', 'Đã gửi email khôi phục mật khẩu.');
    }

    return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
}


    public function reset_password($token)
    {
        $tokenData = AdminResetToken::where('token', $token)->first();
        $user = $tokenData->user ?? null;

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
        }

        return view('admin.reset_password', ['token' => $token]);
    }

    public function check_reset_password(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirm-password' => 'required|same:password',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu ít nhất 8 ký tự',
            'confirm-password.required' => 'Vui lòng nhập lại mật khẩu',
            'confirm-password.same' => 'Mật khẩu không khớp',
        ]);

        $tokenData = AdminResetToken::where('token', $token)->first();
        $user = $tokenData->user ?? null;

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
        }

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $tokenData->delete(); // Xóa token sau khi đặt lại mật khẩu thành công
            return redirect()->route('admin.login')->with('success', 'Mật khẩu đã được cập nhật.');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
    }
}
