@extends('admin.layout.layout')
@section('crumb_parent', 'Khách hàng')
@section('title', 'Cập nhật khách hàng')
@section('main')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
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
            <!-- form-update-customer -->
            <div class="main-content-wrap">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data" action="{{ route('customer.update', $customer->id) }}">
                    @csrf
                    <div class="wg-box">
                        <fieldset class="name">
                            <div class="body-title mb-10">Tên khách hàng <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập tên khách hàng" name="name"
                                value="{{ old('name', $customer->name ?? '') }}">
                        </fieldset>
                        <fieldset class="email">
                            <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="email" placeholder="Nhập email" name="email"
                                value="{{ old('email', $customer->email ?? '') }}">
                        </fieldset>
                        <fieldset class="phone">
                            <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập số điện thoại" name="phone"
                                value="{{ old('phone', $customer->phone ?? '') }}">
                        </fieldset>
                        <fieldset class="phone">
                            <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập số điện thoại" name="phone"
                                value="{{ old('phone', $customer->phone ?? '') }}">
                        </fieldset>
                        <fieldset class="address">
                            <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập số địa chỉ" name="address"
                                value="{{ old('address', $customer->address ?? '') }}">
                        </fieldset>
                        <fieldset class="password">
                            <div class="body-title mb-10">Mật khẩu (để trống nếu không thay đổi)</div>
                            <input class="mb-10" type="password" placeholder="Nhập mật khẩu mới" name="password">
                        </fieldset>
                        <fieldset class="loyalty-level">
                            <div class="body-title mb-10">Cấp độ trung thành</div>
                            <select name="loyalty_level_id" class="form-control">
                                @foreach ($loyaltyLevels as $level)
                                    <option value="{{ $level->id }}" 
                                        {{ $customer->loyalty_level_id == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <fieldset>
                            <div class="body-title">Ảnh đại diện</div>
                            <div class="upload-image flex-grow">
                                <div class="item" id="imgpreview"
                                    style="{{ old('oldImage', $customer->image) ? 'display:block' : 'display:none' }}">
                                    <img style="height: auto !important" class="imgpreview"
                                        src="{{ old('oldImage') ?? asset('uploads/customers/' . $customer->image) }}" alt="">
                                </div>
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Thả hình ảnh vào đây hoặc <span class="tf-color">Bấm để chọn</span></span>
                                        <input class="image" type="file" id="myFile" name="image" accept="image/*">
                                        <input type="hidden" id="oldImage" name="oldImage"
                                            value="{{ old('oldImage', $customer->image ?? '') }}">
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="cols gap10">
                            <button class="tf-button w-full" type="submit">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
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