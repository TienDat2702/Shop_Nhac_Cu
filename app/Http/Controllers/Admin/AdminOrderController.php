<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Tìm kiếm đơn hàng theo id hoặc tên khách hàng
        // if ($request->has('search')) {
        //     $search = $request->input('search');
        //     $query->where('id', 'like', "%{$search}%")
        //         ->orWhereHas('customer', function ($q) use ($search) {
        //             $q->where('name', 'like', "%{$search}%");
        //         });
        // }

        $orders = Order::orderby('id','desc')->Search($request->all());
        $date = Order::Date();
        // dd();
        // Sắp xếp đơn hàng
        if ($request->has('sort') && $request->has('direction')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction') ?? 'desc';

            if ($sort === 'customer_name') {
                $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->orderBy('customers.name', $direction);
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            // Mặc định sắp xếp theo ngày tạo giảm dần (đơn hàng mới nhất trước)
            $query->orderBy('created_at', 'desc');
        }

        // $orders = $query->paginate(10);

        return view('admin.order.index', compact('orders', 'date'));
    }

    public function OrderPending()
    {
        $orders = Order::with(['customer', 'orderDetails'])->where('status', 'chờ xử lý')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.pending', compact('orders'));
    }
    public function search(Request $request)
{
    $orderId = $request->input('order_id');
    $orders = Order::where('id', $orderId)->paginate(10);

    return view('admin.order.pending', compact('orders'));
}

    public function show($id)
    {
        $order = Order::with(['orderDetails.product.brand', 'orderDetails.product.productCategory1', 'customer', 'discount'])->withTrashed()->findOrFail($id);
        $order->is_new = 2;
        $order->save();
        $statuses = ['Chờ xử lý', 'Đã duyệt' , 'Đang chuẩn bị hàng', 'Đang giao', 'Đã giao', 'Đã hủy'];
        return view('admin.order.detail', compact('order', 'statuses'));
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->status;
        $user_note = $request->user_note;

        if ($newStatus === 'Đã giao' && !$order->delivered_at) {
            $order->delivered_at = now();
        } elseif ($newStatus === 'Đã hủy' && !$order->canceled_at) {
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
        $order->is_new = 2;
        $order->save();
        return view('admin.order.detail', compact('order'));
    }
}
