@extends('admin.layout.layout')
@section('title', 'Chỉnh Sửa Showroom')
@section('main')
<style>
    .image-grid {
        display: flex; /* Chuyển sang flexbox để sắp xếp theo hàng ngang */
        flex-wrap: wrap; /* Cho phép các phần tử xuống dòng nếu không còn chỗ */
        gap: 20px; /* Khoảng cách giữa các phần tử */
        justify-content: center; /* Căn giữa các phần tử trong grid */
    }

    .img-preview-item {
        display: flex;
        flex-direction: column;
        align-items: center; /* Căn giữa ảnh và các trường bên dưới */
        text-align: center; /* Căn giữa nội dung */
        width: 150px; /* Chiều rộng cố định cho mỗi ảnh */
    }

    .img-preview-item img {
        width: 100%; /* Đặt chiều rộng ảnh theo container */
        height: 100px; /* Chiều cao cố định */
        object-fit: cover; /* Đảm bảo ảnh được cắt gọn theo kích thước */
        margin-bottom: 10px; /* Khoảng cách giữa ảnh và các trường nhập */
    }

    .upload-container {
        display: flex;
        flex-direction: column;
        align-items: center; /* Căn giữa */
        width: 150px; /* Chiều rộng cố định giống các ảnh */
        height: 100px; /* Chiều cao cố định giống các ảnh */
        border: 2px dashed #ccc; /* Viền cho phần drop image */
        justify-content: center; /* Căn giữa nội dung */
        margin-top: 10px; /* Khoảng cách giữa ảnh và phần upload */
        transition: all 0.3s ease; /* Hiệu ứng chuyển tiếp khi thay đổi kích thước */
    }

    .additional-fields {
        width: 100%; /* Đảm bảo trường nhập cũng chiếm toàn bộ chiều rộng */
    }

    .additional-fields input {
        width: calc(100% - 10px); /* Căn chỉnh độ rộng của input để tránh tràn ra ngoài */
        margin-bottom: 10px; /* Khoảng cách giữa các trường nhập */
    }
</style>
<div class="container">
    <h2>Thêm Showroom</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('banner.store') }}" style="width:200%">
        @csrf <!-- Sử dụng csrf_field() để tự động sinh ra token -->
        <div class="wg-box" id="image-upload-section">
            <fieldset>
                <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                <div class="upload-image flex-grow">
                    <div id="img-previews" class="image-grid"></div> <!-- Thêm class image-grid để hiển thị theo hàng ngang -->
                    <div class="item upload-container" id="upload-container">
                        <label class="uploadfile">
                            <span class="icon">
                                <i class="icon-upload-cloud"></i>
                            </span>
                            <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                            <input type="file" name="images[]" accept="image/*" multiple required>
                        </label>
                    </div>
                </div>
            </fieldset>

            <div class="cols gap10">
                <button class="tf-button w-full" type="submit">Add product</button>
            </div>
        </div>
    </form>

    <script>
        document.querySelector("input[type='file']").addEventListener("change", function(event) {
            const files = event.target.files; // Lấy tất cả các tệp được chọn
            const imgPreviewsContainer = document.getElementById("img-previews");
            imgPreviewsContainer.innerHTML = ""; // Xóa nội dung cũ

            if (files.length > 0) {
                const uploadContainer = document.getElementById("upload-container");
                uploadContainer.style.width = "150px"; // Đặt lại chiều rộng khi có ảnh
                uploadContainer.style.height = "100px"; // Đặt lại chiều cao khi có ảnh

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgPreview = document.createElement("div");
                        imgPreview.className = "img-preview-item";
                        imgPreview.innerHTML = `
                            <img src="${e.target.result}" class="effect8" alt="">
                            <div class="additional-fields">
                                <div class="body-title">Tiêu Đề <span class="tf-color-1">*</span></div>
                                <input type="text" name="title[]" required placeholder="Nhập tiêu đề">
                                <div class="body-title">Tiêu Đề Mạnh <span class="tf-color-1">*</span></div>
                                <input type="text" name="strong_title[]" required placeholder="Nhập tiêu đề mạnh">
                                <div class="body-title">Vị Trí Xuất Hiện <span class="tf-color-1">*</span></div>
                                <input type="number" name="order[]" min="0" required placeholder="VD:1 là slide banner1, 2 là slide banner2,">
                                <div class="body-title">Trang Xuất Hiện <span class="tf-color-1">*</span></div>
                                <input type="number" name="position[]" min="0" required placeholder="VD: 1 xuất hiện ở trang chủ">
                            </div>
                        `;
                        imgPreviewsContainer.appendChild(imgPreview); // Thêm hình ảnh và các trường dưới nó vào container
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
</div>
@endsection
