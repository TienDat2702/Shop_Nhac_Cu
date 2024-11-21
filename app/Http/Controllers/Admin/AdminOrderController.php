<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderDetails'])->withTrashed()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.index', compact('orders'));
    }
    public function OrderPending()
    {
        $orders = Order::with(['customer', 'orderDetails'])->where('status', 'chờ duyệt')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.pending', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.product.brand', 'orderDetails.product.productCategory1', 'customer', 'discount'])->withTrashed()->findOrFail($id);
        $statuses = ['Chờ xử lý', 'Duyệt' , 'Đang giao', 'Đã giao', 'Đã hủy'];
        return view('admin.order.detail', compact('order', 'statuses'));
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->status;
        $user_note = $request->user_note;

        if ($newStatus === 'đã giao' && !$order->delivered_at) {
            $order->delivered_at = now();
        } elseif ($newStatus === 'đã hủy' && !$order->canceled_at) {
            $order->canceled_at = now();
        }

        $order->status = $newStatus;
        $order->user_note = $user_note;
        $order->save();

        return redirect()->route('order.show', $id)->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công');

    
}
public function OrderDetail($id)
    {
        $order = Order::with(['customer', 'orderDetails'])->find($id);
        return view('admin.order.detail', compact('order'));
    }
}
