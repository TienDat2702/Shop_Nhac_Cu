@extends('admin.layout.layout')
@section('crumb_parent', 'Khách hàng')
@section('title', 'Thêm khách hàng')
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
                        <a href="{{ route('customer.index') }}">
                            <div class="text-tiny">@yield('crumb_parent')</div>
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" enctype="multipart/form-data" action="{{ route('customer.store') }}">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên khách hàng <span class="tf-color-1">*</span></div>
                        <input type="text" name="name"  placeholder="Nhập tên khách hàng">
                    </fieldset>
                    <fieldset class="email">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                        <input type="email" name="email"  placeholder="Nhập email">
                    </fieldset>
                    <fieldset class="phone">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input type="text" name="phone"  placeholder="Nhập số điện thoại">
                    </fieldset>
                    <fieldset class="address">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                        <input type="text" name="address" placeholder="Nhập địa chỉ">
                    </fieldset>
                    <fieldset class="password">
                        <div class="body-title mb-10">Mật khẩu <span class="tf-color-1">*</span></div>
                        <input type="password" name="password" placeholder="Nhập mật khẩu">
                    </fieldset>
                    <fieldset class="loyalty-level">
                        <div class="body-title mb-10">Cấp độ trung thành</div>
                        <select name="loyalty_level_id" class="form-control">
                            @foreach ($loyaltyLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Ảnh đại diện</div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{ old('oldImage') ? 'display:block' : 'display:none' }}">
                                <img class="imgpreview" src="{{ old('oldImage') }}" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Thả hình ảnh vào đây hoặc <span class="tf-color">Bấm để chọn</span></span>
                                    <input class="image" type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Thêm mới</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('css')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endsection
@section('script')
    <script src="{{ asset('librarys/upload.js') }}"></script>
    <script>
        var uploadUrl = "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}";
    </script>
@endsection
