<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $customer = Auth::guard('customer')->user(); // Lấy thông tin người dùng hiện tại
        // Trả về view 'profile' và truyền dữ liệu người dùng vào view
        return view('acount-detail', compact('customer', 'loyalty'));
    }
}