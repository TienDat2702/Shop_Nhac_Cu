@extends('admin.layout.layout')

@section('title', 'Thêm Banner')

@section('main')
<div class="container">
    <h2>Thêm Banner</h2>

    <!-- Form Thêm Banner -->
    <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('banner.store') }}">
        @csrf

        <!-- Tiêu đề, Tiêu đề mạnh, Mô tả -->
        <div class="wg-box">
            <fieldset class="title">
                <div class="body-title mb-10">Tiêu Đề <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="text" placeholder="Nhập tiêu đề banner" name="title" maxlength="100" value="{{ old('title') }}">
                @error('title')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>

            <fieldset class="strong_title">
                <div class="body-title mb-10">Tiêu Đề Mạnh (Strong Title) <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="text" placeholder="Nhập tiêu đề mạnh" name="strong_title" maxlength="100" value="{{ old('strong_title') }}">
                @error('strong_title')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>

            <fieldset class="description">
                <div class="body-title mb-10">Mô Tả</div>
                <textarea class="mb-10" placeholder="Nhập mô tả banner" name="description" maxlength="250">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>
        </div>

        <!-- Ảnh, Vị trí, Thứ tự -->
        <div class="wg-box">
            <fieldset>
                <div class="body-title">Upload Hình Ảnh <span class="tf-color-1">*</span></div>
                <div class="upload-image flex-grow">
                    <div class="item" id="imgpreview" style="display:none">
                        <img id="preview" class="effect8" alt="Preview hình ảnh">
                    </div>
                    <div id="upload-file" class="item up-load">
                        <label class="uploadfile" for="image">
                            <span class="icon">
                                <i class="icon-upload-cloud"></i>
                            </span>
                            <span class="body-text">Kéo thả hình ảnh vào đây hoặc <span class="tf-color">nhấp để chọn</span></span>
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>
                </div>
                @error('image')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>

            <fieldset class="position">
                <div class="body-title mb-10">Vị Trí Hiển Thị (Position) <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" placeholder="Nhập vị trí" name="position" min="1" value="{{ old('position') }}">
                @error('position')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>

            <fieldset class="order">
                <div class="body-title mb-10">Thứ Tự Hiển Thị (Order) <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="number" placeholder="Nhập thứ tự" name="order" min="1" value="{{ old('order') }}">
                @error('order')
                    <span class="error-message" style="color: red;">* {{ $message }}</span>
                @enderror
            </fieldset>
        </div>

        <!-- Nút Gửi -->
        <div class="cols gap10">
            <button class="tf-button w-full" type="submit">Thêm Banner</button>
        </div>
    </form>

    <!-- Script Preview Image -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imgpreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</div>
@endsection
