<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        // Đặt giờ cuối ngày cho $endDate
        $endDate = Carbon::parse($endDate)->setTime(23, 59, 59); // 23:59:59

        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        // Đặt giờ đầu ngày cho $startDate (nếu muốn 00:00:00)
        $startDate = Carbon::parse($startDate)->setTime(00, 00, 00); // 00:00:00

        $countOrder = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalOrder = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $countPending = Order::where('status', 'chờ xử lý')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalPending = Order::where('status', 'chờ xử lý')->whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $countDelivered = Order::where('status', 'đã giao')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalDelivered = Order::where('status', 'đã giao')->whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $countCanceled = Order::where('status', 'đã hủy')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalCanceled = Order::where('status', 'đã hủy')->whereBetween('created_at', [$startDate, $endDate])->sum('total');
        
        $orders = Order::with(['customer', 'orderDetails'])
            ->          orderBy('created_at', 'desc')
            ->paginate(5);

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->          groupBy('month')
            ->pluck('total', 'month');

        $monthlyPending = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'chờ xử lý')
            ->           groupBy('month')
            ->pluck('total', 'month');

        $monthlyDelivered = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'đã giao')
            ->           groupBy('month')
            ->pluck('total', 'month');

        $monthlyCanceled = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'đã hủy')
            ->           groupBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard.index', compact('countOrder', 'totalOrder', 'countPending', 'totalPending', 'countDelivered', 'totalDelivered', 'countCanceled', 'totalCanceled', 'orders', 'monthlyRevenue', 'monthlyPending', 'monthlyDelivered', 'monthlyCanceled', 'startDate', 'endDate'));
    }
}
