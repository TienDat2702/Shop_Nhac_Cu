@extends('admin.layout.layout')

@section('crumb_parent', 'Mã Giảm Giá')
@section('title', 'Thêm Mã Giảm Giá')
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
                        <div class="text-tiny">@yield('crumb_parent')</div>
                    </li>
                </ul>
            </div>

            <form action="{{ route('admin.discounts.store') }}" method="POST">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Mã <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="code" id="code" required>
                    </fieldset>
                    <fieldset class="name">
<<<<<<< Updated upstream
                        <div class="body-title mb-10">Tỷ lệ triết khấu <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="discount_rate" id="discount_rate" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giảm lớn nhất <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="max_value" id="max_value" required>
                    </fieldset>
                    <fieldset class="name">
=======
                        <div class="body-title mb-10">Tỷ lệ giảm giá <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="discount_rate" id="discount_rate" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá trị tối đa <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="max_value" id="max_value" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Số lượng mã giảm giá <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="use_limit" id="use_limit" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá tối thiểu để được giảm <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="minimum_order_value" id="minimum_order_value" required>
                    </fieldset>
                    <fieldset class="name">
>>>>>>> Stashed changes
                        <div class="body-title mb-10">Ngày bắt đầu <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="start_date" id="start_date" required>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Ngày kết thúc <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="end_date" id="end_date" required>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Tạo Mã Giảm Giá</button>
                </div>
            </form>
        </div>
    </div>
@endsection

