<style>
    .modal-dialog {
        max-width: 500px;
        margin: 19.75rem auto;
    }
    h5, .h5 {
    font-size: 30px;
    line-height: 28px;
}
.form-select {
    font-size: 16px;
}
.form-label {
    font-size: 17px;
    margin-bottom: .5rem;
}
.modal-footer .btn {
    font-size: 1.5rem; /* Điều chỉnh kích thước chữ (ví dụ: 1.5rem) */
}

</style>
<div class="wg-table table-all-user">
    @php
    $showrooms = ($config == 'index') ? $dsshowroom : $getDeleted;

    @endphp

    @if ($showrooms->isNotEmpty())
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Ảnh</th>
                <th>Địa Chỉ</th>
                <th>Số Điện Thoại</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dsshowroom as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="name">
                        <a href="#" class="body-title-2">{{ $item->name }}</a>
                    </div>
                </td>
                <td>
                    <span><img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                            style="width: 100px; height: 70px;"></span>
                </td>
                <td>
                    {{ $item->address }}
                </td>
                <td>
                    {{ $item->phone }}
                </td>
                <td class="text-center">
                    <form action="{{ route('showroom.togglePublish', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <label class="toggle">
                            <input class="toggleswitch" name="publish" type="checkbox" value="{{ $item->publish }}"
                                data-id="{{ $item->id }}" {{ $item->publish == 2 ? 'checked' : '' }}
                            {{ $config == 'deleted' ? 'disabled' : '' }}
                            onchange="this.form.submit()"> <!-- Tự động submit form khi checkbox thay đổi -->
                            <span class="roundbutton"></span>
                        </label>
                    </form>
                </td>
                <td>
                    <div class="list-icon-function">
                        @if ($config == 'deleted')
                        <a href="{{ route('showroom.restore', $item->id) }}" title="Khôi phục">
                            <div class="item edit">
                                <i class="fa-solid fa-retweet"></i>
                            </div>
                        </a>
                        <form class="form-delete" action="{{ route('showroom.forceDelete', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete item text-danger delete" title="Xóa"
                                data-text="Bạn không thể khôi phục dữ liệu sau khi xóa!">
                                <i class="icon-trash-2"></i>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('showroom.edit', $item->id) }}" title="Chỉnh sửa">
                            <div class="item edit">
                                <i class="icon-edit-3"></i>
                            </div>
                        </a>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProductModal"
                            data-showroom-id="{{ $item->id }}">
                            <i class="fa-solid fa-download"></i>
                        </button>


                        <a href="{{route('Productshowroom.index', $item->id)}}" title="Add Product_Showroom">
                            <div class="item edit">
                                <i class="fa-regular fa-eye"></i>
                            </div>
                        </a>
                        <form class="form-delete" action="{{ route('showroom.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete item text-danger delete" title="Xóa"
                                data-text="Bạn có thể khôi phục dữ liệu lại sau khi xóa."
                                data-text2="{{ ($item->parent_id == 0) ? 'Bạn có thể xóa tất cả danh mục liên quan đến danh mục hiện tại!' : '' }}">
                                <i class="icon-trash-2"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>



            </tr>
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addProductForm" action="{{ route('showroom.addProduct') }}" method="POST">
                @csrf
                <input type="hidden" name="showroom_id" id="showroom_id" value="">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Chọn Sản Phẩm</label>
                        <select id="product_id" name="product_id" class="form-select">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Số lượng (Stock)</label>
                        <input type="number" id="stock" name="stock" class="form-control" min="1" placeholder="Nhập số lượng sản phẩm" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

            @endforeach


        </tbody>
    </table>
    @else
    <div class="not-search text-center">
        <i class="fa-solid fa-circle-exclamation"></i>
        <h3>Không tìm thấy {{ request('keyword') ? '"'.request('keyword').'"' : '' }}</h3>
    </div>
    @endif
</div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var addProductModal = document.getElementById('addProductModal');
        addProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Nút đã được nhấn để mở modal
            var showroomId = button.getAttribute('data-showroom-id'); // Lấy showroom_id

            // Cập nhật giá trị showroom_id vào trường ẩn
            var hiddenInput = document.getElementById('showroom_id');
            hiddenInput.value = showroomId;
        });
    });
</script>