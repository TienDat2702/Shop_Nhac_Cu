<style>
    .form-select {
    margin: 7px;
    display: block;
    height: 45px;
    width: 50%;
    padding: .375rem 2.25rem .375rem .75rem;
    -moz-padding-start: calc(0.75rem - 3px);
    font-size: 15px;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-image: ;
    background-repeat: no-repeat;
    background-position: right .75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>

<div class="wg-table table-all-product">
    <div style="position: relative; padding: 10px; margin-bottom: 15px;">
        <div style="display: flex; align-items: center;">
            <!-- Input tìm showroom -->
            <select id="showroomSearch" class="form-select" aria-label="Default select example">
                <option selected>Chọn Showroom</option>
                @foreach($showroomid as $showroom)
                    <option value="{{ $showroom->id }}">{{ $showroom->name }}</option>
                @endforeach
            </select>

            <!-- Nút Chuyển sản phẩm -->
            <button id="transferBtn" class="btn btn-outline-primary" style="font-size: 16px; padding: 10px; margin-right: 10px;">Chuyển sản phẩm</button>

            <!-- Nút test thử -->
            <a href="{{route('transfer.show')}}" class="btn btn-outline-secondary" style="font-size: 16px; padding: 10px; ">Lịch sử chuyển</a>
        </div>

        <!-- Danh sách gợi ý showroom -->
        <ul id="suggestions" style="list-style-type: none; padding: 0; margin-top: 5px; border: 1px solid #ccc; display: none; background: white; font-size: 16px; width: 300px; position: absolute; z-index: 10;">
            @foreach ($showroomid as $showroom)
                <li data-id="{{ $showroom->id }}" class="suggestion-item" style="padding: 10px; cursor: pointer;">{{ $showroom->name }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Bảng sản phẩm -->
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Chọn</th>
                <th class="text-center">STT</th>
                <th>Tên Sản Phẩm</th>
                <th>Danh mục</th>
                <th>Thương Hiệu</th>
                <th width="100px">Ảnh</th>
                <th>Giá</th>
                <th>Giá khuyến mãi</th>
                <th>Số Lượng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $index => $item)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" class="product-checkbox" data-id="{{ $item->product->id }}" style="margin-right: 5px;">
                        </div>
                    </td>
                    <td class="text-center">{{$index + 1 }}</td>
                    <td>
                        <div class="name">
                            <a href="#" class="body-title-2">{{ $item->product->name }}</a>
                        </div>
                    </td>
                    <td>
                        <div class="category">
                            <a href="#" class="body-title-2">{{ ($item->product->category) ? $item->product->category->name : 'Không có' }}</a>
                        </div>
                    </td>
                    <td>
                        <div class="brand">
                            <a href="#" class="body-title-2">{{ ($item->product->brand) ? $item->product->brand->name : 'Không có' }}</a>
                        </div>
                    </td>
                    <td>
                        <span><img class="img-fluid" src="{{ asset('uploads/products/product/' . $item->product->image) }}" alt=""></span>
                    </td>
                    <td>
                        {{ number_format($item->product->price, 0, ',', '.') }} VNĐ
                    </td>
                    <td>
                        {{ number_format($item->product->price_sale, 0, ',', '.') }} VNĐ
                    </td>
                    <td class="text-center">
                        {{$item->stock}}
                    </td>
                    <td>
                        <div class="list-icon-function">
                            <div class="item edit" type="button" data-bs-toggle="modal" data-bs-target="#updateStockModal"
                                data-showroom-id="{{ $item->showroom->id }}"
                                data-product-id="{{ $item->product->id }}"
                                data-current-stock="{{ $item->stock }}"
                                onclick="setModalData(this)">
                                <i class="icon-edit-3"></i>
                            </div>
                            <input type="number" class="form-control quantity-input" data-id="{{ $item->product->id }}" value="1" min="1" max="{{$item->stock}}" style="width: 50px;">
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Form Cập Nhật Số Lượng trong Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('Productshowroom.update') }}" method="POST">
                @csrf
                <input type="hidden" name="showroom_id" id="modalShowroomId">
                <input type="hidden" name="product_id" id="modalProductId">

                <div class="modal-header">
                    <h5 class="modal-title" id="updateStockModalLabel">Cập Nhật Số Lượng Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Số Lượng</label>
                        <input type="number" name="stock" id="modalStock" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function setModalData(element) {
        const showroomId = element.getAttribute('data-showroom-id');
        const productId = element.getAttribute('data-product-id');
        const currentStock = element.getAttribute('data-current-stock');

        document.getElementById('modalShowroomId').value = showroomId;
        document.getElementById('modalProductId').value = productId;
        document.getElementById('modalStock').value = currentStock;
    }

    $('#transferBtn').on('click', function() {
    const showroomId = $('#showroomSearch').val(); // Lấy ID showroom từ select
    const products = [];

    $('.product-checkbox:checked').each(function() {
        const productId = $(this).data('id');
        const quantity = $(this).closest('tr').find('.quantity-input').val();

        products.push({
            id: productId,
            quantity: quantity,
        });
    });

    if (products.length === 0 || !showroomId) {
        toastr.error('Vui lòng chọn ít nhất một sản phẩm và showroom.'); // Thông báo lỗi
        return;
    }

    // Gửi yêu cầu AJAX
    $.ajax({
        url: '{{ route('transfer.product') }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            showroom_id: showroomId,
            products: products,
        },
        success: function(response) {
            // Lưu thông báo vào sessionStorage
            sessionStorage.setItem('successMessage', response.message);
            location.reload(); // Tải lại trang
        },
        error: function(xhr) {
            // Lưu thông báo lỗi vào sessionStorage
            sessionStorage.setItem('errorMessage', xhr.responseJSON.message || 'Đã xảy ra lỗi, vui lòng thử lại.');
            location.reload(); // Tải lại trang
        }
    });
});

// Khi trang được tải lại, kiểm tra và hiển thị thông báo nếu có
$(document).ready(function() {
    const successMessage = sessionStorage.getItem('successMessage');
    const errorMessage = sessionStorage.getItem('errorMessage');

    if (successMessage) {
        toastr.success(successMessage); // Hiển thị thông báo thành công
        sessionStorage.removeItem('successMessage'); // Xóa thông báo sau khi hiển thị
    }

    if (errorMessage) {
        toastr.error(errorMessage); // Hiển thị thông báo lỗi
        sessionStorage.removeItem('errorMessage'); // Xóa thông báo sau khi hiển thị
    }
});

</script>
