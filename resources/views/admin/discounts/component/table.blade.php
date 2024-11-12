<div class="wg-table table-all-brand">
    
    @php
        $brands = ($config == 'index') ? $brands : $getDeleted;
    @endphp

    @if ($brands->isNotEmpty())
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">STT</th>
<<<<<<< Updated upstream
                <th>Tên Thương Hiệu</th>
                <th width="100px">Ảnh</th>
=======
                <th>Tên Giảm Giá</th>
                <th width="100px">Giá Trị</th>
>>>>>>> Stashed changes
                <th>Mô Tả</th>
                <th>Trạng Thái</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $index => $item)
                <tr>
                    <td class="text-center">{{ $brands->currentPage() * $brands->perPage() - $brands->perPage() + $index + 1 }}</td>
                    <td>
                        <div class="name">
<<<<<<< Updated upstream
                            <a href="#" class="body-title-2">{{ $item->name }}</a>
                        </div>
                    </td>
                    <td>
                        <span>
                            <img class="img-fluid" src="{{ asset('uploads/brands/' . $item->image) }}" alt="{{ $item->name }}" onerror="this.onerror=null;this.src='path/to/default/image.png';">
                        </span>
=======
                            <a href="#" class="body-title-2">{{ $item->discount_name }}</a>
                        </div>
                    </td>
                    <td>
                        {{ $item->discount_value }}
>>>>>>> Stashed changes
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td class="text-center">
                        <label class="toggle">
                            <input id="toggleswitch" class="toggleswitch" name="publish" type="checkbox"
<<<<<<< Updated upstream
                                value="{{ $item->publish }}" data-id="{{ $item->id }}" data-model="Brand"
=======
                                value="{{ $item->publish }}" data-id="{{ $item->id }}" data-model="Discount"
>>>>>>> Stashed changes
                                {{ $item->publish == 2 ? 'checked' : '' }}
                                {{ $config == 'deleted' ? 'disabled' : '' }}>
                            <span class="roundbutton"></span>
                        </label>
                    </td>
                    <td>
                        <div class="list-icon-function">
                            @if ($config == 'deleted')
<<<<<<< Updated upstream
                                <a href="{{ route('brand.restore', $item->id) }}" title="Khôi phục">
=======
                                <a href="{{ route('discount.restore', $item->id) }}" title="Khôi phục">
>>>>>>> Stashed changes
                                    <div class="item edit">
                                        <i class="fa-solid fa-retweet"></i>
                                    </div>
                                </a>
<<<<<<< Updated upstream
                                <form class="form-delete" action="{{ route('brand.forceDelete', $item->id) }}" method="POST">
=======
                                <form class="form-delete" action="{{ route('discount.forceDelete', $item->id) }}" method="POST">
>>>>>>> Stashed changes
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete item text-danger delete" title="Xóa" 
                                    data-text="Bạn không thể khôi phục dữ liệu sau khi xóa!">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
                            @else
<<<<<<< Updated upstream
                                <a href="{{ route('brand.edit', $item->id) }}" title="Chỉnh sửa">
=======
                                <a href="{{ route('discount.edit', $item->id) }}" title="Chỉnh sửa">
>>>>>>> Stashed changes
                                    <div class="item edit">
                                        <i class="icon-edit-3"></i>
                                    </div>  
                                </a>
<<<<<<< Updated upstream
                                <form class="form-delete" action="{{ route('brand.destroy', $item->id) }}" method="POST">
=======
                                <form class="form-delete" action="{{ route('discount.destroy', $item->id) }}" method="POST">
>>>>>>> Stashed changes
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
        {{ $brands->appends(request()->all())->links() }}
    </ul>
</nav>