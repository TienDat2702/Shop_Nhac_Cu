@extends('admin.layout.layout')
@section('title', 'Danh sách đơn hàng')
@section('main')
 <style>
    th {
    position: relative;
}

.sort-icon {
    position: absolute;
    right: 10px; /* Điều chỉnh khoảng cách từ lề phải */
    top: 50%;
    transform: translateY(-50%);
}
</style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Đơn hàng</h3>
                
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('dashboard.index') }}">
                            <div class="text-tiny">Bảng điều khiển</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Đơn hàng</div>
                    </li>
                </ul>
            </div>
    
            <div class="wg-box">
                <form method="GET" action="{{ route('order.index') }}" class="flex items-center">
                    <input type="text" name="search" placeholder="Tìm kiếm đơn hàng" value="{{ request('search') }}" class="form-control">
                    <button type="submit" class="btn btn-primary p-3 fs-3 w-25">Tìm kiếm</button>
                </form>            
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">
                                        Mã
                                        <a href="{{ route('order.index', ['sort' => 'id', 'direction' => request('direction') == 'desc' ? 'asc' : 'desc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'id' ? (request('direction') == 'desc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        Tên khách hàng
                                        <a href="{{ route('order.index', ['sort' => 'customer_name', 'direction' => request('direction') == 'desc' ? 'asc' : 'desc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'customer_name' ? (request('direction') == 'desc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        Tổng tiền hàng
                                        <a href="{{ route('order.index', ['sort' => 'total', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'total' ? (request('direction') == 'asc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">
                                        Ngày đặt hàng
                                        <a href="{{ route('order.index', ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'created_at' ? (request('direction') == 'asc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        Số lượng sản phẩm
                                        <a href="{{ route('order.index', ['sort' => 'product_quantity', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'product_quantity' ? (request('direction') == 'asc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        Ngày giao hàng
                                        <a href="{{ route('order.index', ['sort' => 'delivered_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'delivered_at' ? (request('direction') == 'asc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        Hành động
                                        <a href="{{ route('order.index', ['sort' => 'action', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sort-icon">
                                            <i class="fa {{ request('sort') == 'action' ? (request('direction') == 'asc' ? 'fa-angle-double-down' : 'fa-angle-double-up') : 'fa-sort' }}" aria-hidden="true"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($orders) > 0)
                                    @foreach($orders as $order)
                                    <tr style="{{ $order->is_new < 2 ? 'background-color: #a8c7fa54;' : '' }}">
                                        <td class="text-center">#{{ $order->id }}</td>
                                        <td class="text-center">{{ $order->customer->name }}</td>
                                        <td class="text-center">{{ $order->discount ? number_format($order->total - $order->discount->discount_rate) : number_format($order->total) }} VND</td>
                                        <td class="text-center text-uppercase">{{ $order->status }}</td>
                                        <td class="text-center">{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="text-center">{{ $order->orderDetails->sum('quantity') }}</td>
                                        <td class="text-center text-uppercase">{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : $order->status }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('order.show', $order->id) }}">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="py-4 text-center" >
                                            <h4>Hiện tại chưa có đơn hàng nào !!!</h4>
                                        </td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
    
@endsection

