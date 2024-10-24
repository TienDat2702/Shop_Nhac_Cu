<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderDetails'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.index', compact('orders'));
    }
    public function OrderPending()
    {
        $orders = Order::with(['customer', 'orderDetails'])->where('status', 'chờ duyệt')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.pending', compact('orders'));
    }
    public function OrderDetail($id)
    {
        $order = Order::with(['customer', 'orderDetails'])->find($id);
        return view('admin.order.detail', compact('order'));
    }
}
