@extends('admin.layout.layout')
@section('title', 'Cập Nhật Mã Giảm Giá')
@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>@yield('title')</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('dashboard.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">@yield('title')</div>
                </li>
            </ul>
        </div>
<div class="container">
    <form action="{{ route('discount.update', $discount->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="wg-box">
            <div class="row">
                <div class="form-group col-6">
                    <label for="code" class="body-title mb-10" >Tên Mã Giảm Giá</label>
                    <input type="text" name="name" class="form-control" value="{{ $discount->name}}">
                </div>
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="discount_rate" >Tỷ Lệ Giảm Giá (%)</label>
                    <input type="number" name="discount_rate" class="form-control"  value="{{ $discount->discount_rate }}">
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="max_value" >Giá Trị Giảm Tối Đa</label>
                    <input type="number" name="max_value" class="form-control" value="{{ $discount->max_value }}">
                </div>
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="use_limit">Giới Hạn Sử Dụng</label>
                    <input type="number" name="use_limit" class="form-control"  value="{{ $discount->use_limit }}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="max_value">Đơn hàng tối thiểu</label>
                    <input type="number" name="minimum_order_value" class="form-control" value="{{ $discount->minimum_order_value }}">
                </div>
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="use_limit">Tổng giá tối thiểu</label>
                    <input type="number" name="minimum_total_value" class="form-control" value="{{ $discount->minimum_total_value}}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="start_date"
                    > Ngày Bắt Đầu</label>
                    <input type="date" name="start_date" class="form-control"  value="{{ $discount->start_date }}">
                </div>
                <div class="form-group col-6">
                    <label class="body-title mb-10" for="end_date" >Ngày Kết Thúc</label>
                    <input type="date" name="end_date" class="form-control"  value="{{ $discount->end_date }}">
                </div>
            </div>
        
        <div class="d-flex justify-content-center">
            <button class="tf-button px-5" type="submit">Cập Nhật</button>
        </div>
    </form>
</div>
@endsection