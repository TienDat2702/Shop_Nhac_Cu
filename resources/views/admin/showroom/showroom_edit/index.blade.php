@extends('admin.layout.layout')
@section('title', 'Chỉnh Sửa Showroom')
@section('main')
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <h2>Edit Showroom</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="tf-section-2 form-add-product" action="{{ route('showroom.update', $showroom->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Tên showroom <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Nhập tên showroom" name="name" value="{{ old('name', $showroom->name) }}" required>
                </fieldset>

                <fieldset class="address">
                    <div class="body-title mb-10">Địa chỉ</div>
                    <input class="mb-10" type="text" placeholder="Nhập địa chỉ showroom" name="address" value="{{ old('address', $showroom->address) }}">
                </fieldset>

                <fieldset class="phone">
                    <div class="body-title mb-10">Điện thoại</div>
                    <input class="mb-10" type="text" placeholder="Nhập số điện thoại" name="phone" value="{{ old('phone', $showroom->phone) }}">
                </fieldset>

                <fieldset>
                    <div class="body-title">Hình ảnh</div>
                    <div class="upload-image">
                    <div class="item" id="imgpreview" style="{{ old('image', $showroom->image) ? 'display:block' : 'display:none' }}">
                                    <img class="imgpreview" src="{{ old('oldImage') ?? asset($showroom->image) }}" class="effect8" alt="">
                                </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="image">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Thả hình ảnh của bạn vào đây hoặc chọn <span class="tf-color">Bấm để duyệt</span></span>
                                <input class="image" type="file" id="image" name="image" accept="image/*">
                                <input type="hidden" id="oldImage" name="oldImage" value="{{ $showroom->image }}">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Cập nhật showroom</button>
                </div>
            </div>
        </form>
        <!-- /form-update-showroom -->
    </div>
    <!-- /main-content-wrap -->
</div>

@endsection
