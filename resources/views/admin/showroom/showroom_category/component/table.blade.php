
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
        data-text2="Bạn có thể xóa tất cả danh mục liên quan đến danh mục hiện tại!"
        data-has-products="{{ $item->products()->count() > 0 ? 'true' : 'false' }}"
        data-publish="{{ $item->publish }}">
        <i class="icon-trash-2"></i>
    </button>
</form>


                        @endif
                    </div>
                </td>



            </tr>

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
<nav aria-label="Page navigation example">
    <ul class="pagination d-flex justify-content-center my-3">
        {{ $dsshowroom->appends(request()->all())->links() }} <!-- Thêm links phân trang -->
    </ul>
</nav>
<form class="form-transfer-all" action="{{ route('showroom.transferAll', $item->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-transfer-all text-success" title="Chuyển tất cả sản phẩm">
        Chuyển tất cả sản phẩm
    </button>
</form>


</div>
