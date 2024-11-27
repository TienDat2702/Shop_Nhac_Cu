<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::guard('customer')->user()) {
            // Lấy thông tin khách hàng từ cơ sở dữ liệu
            $customer = Customer::find(Auth::guard('customer')->user()->id);
            
            // Kiểm tra nếu khách hàng bị chặn (publish != 2)
            if ($customer->publish !== 2) {
                Auth::guard('customer')->logout(); // Đăng xuất khách hàng
                toastr()->error('Bạn đã bị chặn không thể đăng nhập'); // Thông báo lỗi
                return redirect()->route('customer.login'); // Chuyển hướng tới trang đăng nhập
            }
        }
    
        return $next($request);
    }
}
