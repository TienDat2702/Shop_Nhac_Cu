@extends('admin.layout.layout')
@section('title', 'Chỉnh Sửa Banner')
@section('main')
<div class="container">
    <h2>Edit Showroom</h2>
    <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="wg-box">
    <fieldset>
    <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
    <div class="upload-image flex-grow">
        <div class="item" id="imgpreview" style="display:none">
            <img src="{{ asset('storage/images/showrooms/upload-1.png') }}" class="effect8" alt="">
        </div>
        <div id="upload-file" class="item up-load">
            <label class="uploadfile" for="myFile">
                <span class="icon">
                    <i class="icon-upload-cloud"></i>
                </span>
                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                <input type="file" id="myFile" name="image" accept="image/*" required> <!-- Thêm 'required' nếu cần -->
            </label>
        </div>
    </div>
</fieldset>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script>
    document.getElementById("myFile").addEventListener("change", function(event) {
        const imgPreview = document.getElementById("imgpreview");
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.style.display = "block";
                imgPreview.querySelector("img").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
