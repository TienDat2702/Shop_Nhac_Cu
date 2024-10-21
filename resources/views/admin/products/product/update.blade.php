@extends('admin.layout.layout')
@section('crumb_parent', 'Sản phẩm')
@section('title', 'Cập nhật sản phẩm')
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
                        <a href="{{ route('product.index') }}">
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
            <!-- form-update-product -->
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
                <form class="tf-section-2 form-add-product" method="post" enctype="multipart/form-data"
                    action="{{ route('product.update', $product->id) }}">
                    @csrf
                    <div class="wg-box">
                        <fieldset class="name">
                            <div class="body-title mb-10">Tên sản phẩm <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập tên sản phẩm" name="name"
                            value="{{ old('name', $product->name) }}">
                        </fieldset>

                        <fieldset class="price">
                            <div class="body-title mb-10">Giá <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" step="10000" min="0" placeholder="Nhập giá sản phẩm" name="price"
                            value="{{ old('price', intval($product->price)) }}">
                        </fieldset>
                        
                        <fieldset class="price_sale">
                            <div class="body-title mb-10">Giá khuyến mãi</div>
                            <input class="mb-10" type="number" step="10000" min="0" placeholder="Nhập giá khuyến mãi" name="price_sale"
                            value="{{ old('price_sale', intval($product->price_sale)) }}">
                        </fieldset>
                        
                        
                        <fieldset class="description">
                            <div class="body-title mb-10">Mô tả</div>
                            <textarea class="mb-10" placeholder="Nhập mô tả" name="description">{{ old('description', $product->description) }}</textarea>
                        </fieldset>
                    </div>
                    <div class="wg-box">
                        <div class="gap22 cols">
                            <fieldset class="category">
                                <div class="body-title mb-10">Danh mục</div>
                                <div class="select">
                                    <select name="category_id">
                                        <option value="0">--Chọn danh mục--</option>
                                        @foreach ($categories as $val)
                                            <option 
                                            @if ($val->id == old('category_id', $product->category_id)) selected
                                            @endif
                                            value="{{ $val->id }}">
                                                {{ $val->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>

                            <fieldset class="brand">
                                <div class="body-title mb-10">Thương hiệu</div>
                                <div class="select">
                                    <select name="brand_id">
                                        <option value="0">--Chọn thương hiệu--</option>
                                        @foreach ($brands as $val)
                                            <option 
                                            @if ($val->id == old('brand_id', $product->brand_id)) selected
                                            @endif
                                            value="{{ $val->id }}">
                                                {{ $val->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <fieldset>
                            <div class="body-title">Upload images</div>
                            <div class="upload-image flex-grow">
                                <div class="item" id="imgpreview" style="{{ $product->image ? 'display:block' : 'display:none' }}">
                                    <img class="imgpreview" src="{{ asset('uploads/products/product/' . $product->image) }}" class="effect8" alt="">
                                </div>
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Thả hình ảnh của bạn vào đây hoặc chọn <span class="tf-color">Bấn để duyệt</span></span>
                                        <input class="image" type="file" id="myFile" name="image" accept="image/*">
                                        <input type="hidden" id="oldImage" name="oldImage" value="{{ $product->image }}">
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="cols gap10">
                            <button class="tf-button w-full" type="submit">Cập nhật sản phẩm</button>
                        </div>
                    </div>
                </form>
                <!-- /form-update-product -->
            </div>
            <!-- /main-content-wrap -->
        </div>
    @endsection

@section('script')
    <script src="{{ asset('librarys/upload.js') }}"></script>
@endsection
