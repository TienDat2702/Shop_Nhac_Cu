<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class AdminController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }
        
        return view('admin.login');
    }
    

    public function check_login(AuthRequest $request)
    {
        $data = $request->only('email', 'password');
        
        if (auth()->attempt($data)) {
            return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
        }
        
        return redirect()->back()->with('error', 'Tài khoản không chính xác. Vui lòng đăng nhập lại!');
    }


    public function logout()
{
    auth()->logout();
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}
}
