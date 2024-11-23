@extends('admin.layout.layout')
@section('title', 'Chi tiết đơn hàng')
@section('main')
    <style type="text/css" media="print">
        @media print {
    body {
        font-size: 12pt;
        margin: 0;
        padding: 0;
    }

    .wg-box {
        display: block !important;
    }

    .tf-button, .header, .footer {
        display: none !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }

    img {
        max-width: 100%;
        height: auto;
    }
}

    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết đơn hàng #{{ $order->id }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Chi tiết đơn hàng #{{ $order->id }}</div>
                    </li>
                </ul>
            </div>
            @if ($order->status == 'Đã nhận hàng' || $order->status == 'Đã hủy')
                <div style="background-color: {{ $order->status == 'Đã nhận hàng' ? '#29be29' : '#f16161'  }}"  class="notification">
                    <span>{{ $order->status }}</span>
                </div>
            @endif
           
            <div>
                {{-- <a class="tf-button style-1 w208 mr-2" href="{{ route('order.index') }}">Quay lại</a> --}}
                <button class="tf-button style-1 w208" onclick="printOrder()">In đơn hàng</button>
            </div>
            <div style="margin-bottom: 30px" class="row">
                <div class="col-lg-6">
                    <div class="my-account wg-box mt-5">
                        <h5>Thông tin khách hàng</h5>
                        <div class="my-account__address-item__detail">
                            <p class="mb-2"><strong>Địa chỉ :</strong> {{ $order->address }}</p>
                            <p class="mb-2"><strong>Tên : </strong>{{ $order->customer->name }}</p>
                            <p class="mb-2"><strong>Email : </strong>{{ $order->customer->email }}</p>
                            <p class="mb-2"><strong>Số điện thoại :</strong> {{ $order->customer->phone }}</p>
                            <p class="mb-2"><strong>Phương thúc thanh toán :</strong> {{ $order->payment_method }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="my-account wg-box mt-5">
                        <h5>Thông tin giao hàng</h5>
                        <div class="my-account__address-item">
                            <div class="my-account__address-item__detail">
                                <p class="mb-2"><strong>Địa chỉ giao hàng :</strong> {{ $order->address }}</p>
                                <p class="mb-2"><strong>Tên người nhận : </strong>{{ $order->name }}</p>
                                <p class="mb-2"><strong>Số điện thoại :</strong> {{ $order->phone }}</p>
                                <p class="mb-2"><strong>Ghi chú :</strong> {{ $order->customer_note }}</p>
                                <p class="mb-2"><strong>Ghi chú cửa hàng :</strong> {{ $order->user_note }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Sản phẩm đơn hàng</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
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
                                            <img src="{{ asset('uploads/products/product/' . $orderDetail->product->image) }}" alt="" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="#" target="_blank"
                                                class="body-title-2">{{ $orderDetail->product->name }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($orderDetail->price) }} VNĐ</td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">{{ $orderDetail->product->brand->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $orderDetail->product->productCategory->name ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tổng tiền</th>
                                <td class="ps-5" colspan="5">{{ number_format($order->total) }}VNĐ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- <div class="table-responsive">
                    <h5 style="margin-bottom: 20px">Thông tin đơn hàng</h5>
                    <table class="table table-striped table-bordered table-transaction">
                        <tbody>
                            <tr>
                                <th>Tổng tiền</th>
                                <td>{{ number_format($order->total) }}VNĐ</td>
                            </tr>
                            <tr>
                                <th>Phương thức thanh toán</th>
                                <td>{{ $order->payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td class="text-uppercase font-bold">{{ $order->status }}</td>   
                            </tr>
                            <tr>
                                <th>Ngày đặt hàng</th>
                                <td> {{ date('d/m/Y', strtotime($order->created_at)) }}</td>    
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                </div>
            </div>

            @if ($order->discount_id)
                <div class="wg-box mt-5">
                    <h5>Mã giảm giá</h5>
                    <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__detail">
                            <p>{{ $order->discount->code }}</p>
                            <br>
                            <p>Số tiền được giảm: {{ $order->discount->value }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="wg-box mt-5">
                <h5>Cập nhật trạng thái đơn hàng</h5>
                <div class="my-account__address-item">
                    <div class="my-account__address-item__detail ">
                        <form class="row" action="{{ route('order.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="form-label fw-bold h6">Trạng thái:</label>
                                <select {{ $order->status == 'Đã nhận hàng' ? 'disabled' : '' }} name="status" id="status" class="form-select form-select-lg mt-2  fs-4 py-3">
                                    @if ($order->status == 'Đã nhận hàng')
                                        <option class="fs-4" value="Đã nhận hàng" selected>Đã nhận hàng</option>
                                    @else
                                        @foreach ($statuses as $status)
                                            <option class="fs-4" value="{{ $status }}"
                                            {{ $order->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    @endif
                                   
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="user_note" class="form-label fw-bold h6">Ghi chú:</label>
                               <textarea name="user_note">{{ $order->user_note ?? old('user_note') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-center mt-5">
                                <button type="submit" class="tf-button style-1 mt-4 h5">Cập nhật đơn hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




        </div>
    @endsection
    @section('script')
        <script>
            function printOrder() {
                window.print();
            }
        </script>
    @endsection

