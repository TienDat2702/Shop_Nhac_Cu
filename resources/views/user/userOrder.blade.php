@extends('user.layouts.app')
@section('content')
    <main class="pt-90">
        <main class="pt-90" style="padding-top: 0px;">
            <div class="mb-4 pb-4"></div>
            <section class="my-account container">
                <h2 class="page-title">Orders</h2>
                <div class="row">
                    <div class="col-lg-2">
                        <ul class="account-nav">
                            @include('user.layouts.component.sidebarUser')
                        </ul>
                    </div>

                    <div class="col-lg-10">
                        <div class="wg-table table-all-user">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Mã đơn hàng</th>
                                            <th class="text-center">Tổng tiền</th>
                                            {{-- <th class="text-center">Mã giảm giá</th>
                                            <th class="text-center">Giá giảm</th>
                                            <th class="text-center">Tổng cộng</th> --}}
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Ngày đặt</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Ngày giao</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                        <tr>
                                            <td class="text-center">#{{ $order->id }}</td>
                                            <td class="text-center">{{ number_format($order->total) }} VND</td>
                                            {{-- <td class="text-center">{{ $order->coupon_code }}</td>
                                            <td class="text-center">{{ $order->discount_amount }}</td>
                                            <td class="text-center">{{ $order->total_amount }}</td> --}}

                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $order->status }}</span>
                                            </td>
                                            <td class="text-center">{{ $order->created_at }}</td>
                                            <td class="text-center">{{ $order->orderDetails->sum('quantity') }}</td>
                                            <td>
                                                @if ($order->delivery_date)
                                                    {{ $order->delivery_date }}
                                                @else
                                                    Đang chờ giao hàng
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="account-orders-details.html">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="fa fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                        </div>
                    </div>

                </div>
            </section>
        </main>
    @endsection
