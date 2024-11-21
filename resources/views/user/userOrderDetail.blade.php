@extends('user.layouts.app')
@section('title', 'Chi tiết đơn hàng')
@section('content')
    <style type="text/css" media="print">
        @media print {

            .tf-button,
            .wg-box:last-child {
                display: none !important;
            }

            body {
                font-size: 12pt;
            }

            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
        }
        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }
        .btn-primary {
            background-color: #6a6e51 !important;
            border-color: #6a6e51 !important;
        }
    </style>
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Chi tiết đơn hàng #{{ $order->id }}</h2>
            <div class="row">
                <div class="col-lg-2">
                    <ul class="account-nav">
                        @include('user.layouts.component.sidebarUser')
                    </ul>
                </div>

                <div class="col-lg-10">
                    <div class="text-right d-flex justify-content-end">
                        <a class="btn btn-sm btn-danger me-3" href="{{ route('customer.orders') }}">Quay lại</a>
                        <button class="btn btn-sm btn-primary" onclick="printOrder()">In đơn hàng</button>
                    </div>
                    <div class="wg-box mt-5">
                        <div class="row">
                            <div class="col-lg-6">
                                <h3 style="font-weight: 600;">Thông tin khách hàng</h3>
                                <div class="my-account__address-item mt-3">
                                    <div style="font-size: 16px;" class="my-account__address-item__detail">
                                        <p class="mb-2"><strong>Địa chỉ :</strong> {{ $order->address }}</p>
                                        <p class="mb-2"><strong>Tên : </strong>{{ $order->customer->name }}</p>
                                        <p class="mb-2"><strong>Email : </strong>{{ $order->customer->email }}</p>
                                        <p class="mb-2"><strong>Số điện thoại :</strong> {{ $order->customer->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h3 style="font-weight: 600;">Thông tin Giao hàng</h3>
                                <div class="my-account__address-item mt-3">
                                    <div style="font-size: 16px;" class="my-account__address-item__detail">
                                        <p class="mb-2"><strong>Địa chỉ giao hàng :</strong> {{ $order->address }}</p>
                                        <p class="mb-2"><strong>Tên người nhận : </strong>{{ $order->name }}</p>
                                        <p class="mb-2"><strong>Số điện thoại :</strong> {{ $order->phone }}</p>
                                        <p class="mb-2"><strong>Phương thức thanh toán :</strong> {{ $order->payment_method }}</p>
                                        <p class="mb-2"><strong>Ghi chú :</strong> {{ $order->customer_note }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    {{-- Theo dõi đơn hàng --}}
                    @if ($order->status != 'Đã nhận hàng')
                        <h3 style="font-weight: 600;" class="mt-4">Tình trạng đơn hàng</h3>
                        @include('user.partials.progress_tracker')
                    @endif
                    {{-- end Theo dõi đơn hàng --}}

                    <div class="wg-box mt-5 mb-5">
                        <div class="row mb-2">
                            <div class="col-6">
                                <h3 style="font-weight: 600;">Sản phẩm đơn hàng</h3>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-transaction">
                                <thead>
                                    <tr>
                                        <th class="text-center">Mã sản phẩm</th>
                                        <th>Tên sản phẩm</th>
                                        <th class="text-center">Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Thương hiệu</th>
                                        <th class="text-center">Danh mục</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $orderDetail)
                                        <tr>
                                            <td class="text-center">#{{ $orderDetail->product->id }}</td>
                                            <td class="pname">
                                                <div class="image">
                                                    <img src="{{ asset('uploads/products/product/' .$orderDetail->product->image) }}" alt="" class="image">
                                                </div>
                                                <div class="name">
                                                    <a href="#" target="_blank" class="body-title-2">{{ $orderDetail->product->name }}</a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($orderDetail->product->price_sale)
                                                    {{ number_format($orderDetail->product->price_sale) }} VNĐ
                                                @else
                                                    {{ number_format($orderDetail->product->price) }} VNĐ
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $orderDetail->quantity }}</td>
                                            <td class="text-center">{{ $orderDetail->product->brand->name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $orderDetail->product->productCategory->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            Tổng Thanh Toán
                                        </td>
                                        <td colspan="5">
                                            {{ number_format($order->total) }} VNĐ
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="wg-box mt-5 text-right">
                        @if ($order->status =='Chờ xử lý' || $order->status =='Chưa xác nhận')
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button type="submit" class="btn bg-danger">Hủy đơn hàng</button>
                            </form>
                        @elseif($order->status =='Đã giao')
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button type="submit" class="btn bg-success">Đã nhận hàng</button>
                            </form>
                        @else
                            <a href="{{ route('customer.orders') }}" class="btn btn-primary">Quay lại</a>
                        @endif
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection

@section('script')
    <script>
        function printOrder() {
            window.print();
        }
    </script>
@endsection
