@extends('admin.layout.layout')

@section('main')
<div class="main-content-inner">
<div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Chi tiết đơn hàng</h3>
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
                <div class="text-tiny">Chi tiết đơn hàng</div>
            </li>
        </ul>
    </div>

    <div class="wg-box">
        <div class="flex items-center justify-between gap10 flex-wrap">
            <div class="wg-filter flex-grow">
                <h5>Sản phẩm đơn hàng</h5>
            </div>
            <a class="tf-button style-1 w208" href="{{ route('order.index') }}">Quay lại</a>
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
                    <tr>
                        <td class="text-center">SHT01245</td>
                        <td class="pname">
                            <div class="image">
                                <img src="1718066538.html" alt="" class="image">
                            </div>
                            <div class="name">
                                <a href="#" target="_blank"
                                    class="body-title-2">Product1</a>
                            </div>
                        </td>
                        <td class="text-center">$71.00</td>
                        <td class="text-center">1</td>
                        <td class="text-center">Category1</td>
                        <td class="text-center">Brand1</td>
                    </tr>
                    <tr>

                        <td class="text-center">SHT01245</td>
                        <td class="pname">
                            <div class="image">
                                <img src="1718066673.html" alt="" class="image">
                            </div>
                            <div class="name">
                                <a href="#" target="_blank"
                                    class="body-title-2">Product2</a>
                            </div>
                        </td>
                        <td class="text-center">$101.00</td>
                        <td class="text-center">1</td>
                        <td class="text-center">Category2</td>
                        <td class="text-center">Brand1</td>                             
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="divider"></div>
        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

        </div>
    </div>

    <div class="wg-box mt-5">
        <h5>Địa chỉ giao hàng</h5>
        <div class="my-account__address-item col-md-6">
            <div class="my-account__address-item__detail">
                <p>{{ $order->address }}</p>
                <br>
                <p>{{ $order->customer->name }}</p>
                <br>
                <p>Số điện thoại: {{ $order->customer->phone }}</p>
            </div>
        </div>
    </div>

    <div class="wg-box mt-5">
        <h5>Thanh toán</h5>
        <table class="table table-striped table-bordered table-transaction">
            <tbody>
                <tr>
                    <th>Tổng tiền</th>
                    <td>172.00</td>
                    <th>Thué</th>
                    <td>36.12</td>
                    <th>Giảm giá</th>
                    <td>0.00</td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td>208.12</td>
                    <th>Phương thức thanh toán</th>
                    <td>cod</td>
                    <th>Trạng thái</th>
                    <td>chờ duyệt</td>
                </tr>
                <tr>
                    <th>Ngày đặt hàng</th>
                    <td>2024-07-11 00:54:14</td>
                    <th>Ngày giao hàng</th>
                    <td></td>
                    <th>Ngày hủy</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
