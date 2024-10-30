@extends('user.layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Thông tin tài khoản</h2>
            <div class="row">
                <div class="col-lg-3">
                    <ul class="account-nav">
                        @include('user.layouts.component.sidebarUser')
                    </ul>
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <p>Xin chào <strong>{{ $customer->name }}</strong> </p>
                        <p>Thành viên <strong>{{ $loyalty->level_name}} </strong></p>
                        <p>Từ trang quản lý tài khoản của bạn, bạn có thể xem các <a class="unerline-link"
                                href="account_orders.html">đơn hàng gần đây</a>, quản lý các <a class="unerline-link"
                                href="account_edit_address.html">địa chỉ giao hàng</a>, và <a class="unerline-link"
                                href="account_edit.html">chỉnh sửa mật khẩu và thông tin tài khoản</a>.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
