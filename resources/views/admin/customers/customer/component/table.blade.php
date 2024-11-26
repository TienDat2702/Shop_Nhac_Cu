<div class="wg-table table-all-customer">
    @php
        $customers = ($config == 'index') ? $customers : $getDeleted;
    @endphp

    @if ($customers->isNotEmpty())
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th width="100px !important">Ảnh</th>
                <th>Tên khách hàng</th>
                <th>Email</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($customers as $index => $item)
                    <tr>
                        <td class="text-center">{{ $customers->currentPage() * $customers->perPage() - $customers->perPage() + $index + 1 }}</td>
                        <td width="100px !important">
                            <span><img class="img-fluid" src="{{ asset('uploads/customers/' . $item->image ) }}" alt=""></span>
                        </td>
                        <td>
                            <div class="name">
                                <a href="#" class="body-title-2">{{ $item->name }}</a>
                            </div>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                        <td class="text-center">
                            <label class="toggle">
                                <input id="toggleswitch" class="toggleswitch" name="publish" type="checkbox"
                                    value="{{ $item->publish }}" data-id="{{ $item->id }}" data-model="Customer"
                                    {{ $item->publish == 2 ? 'checked' : '' }}
                                    {{ $config == 'deleted' ? 'disabled' : '' }}>
                                <span class="roundbutton"></span>
                            </label>
                        </td>
                        <td>
                            <div class="list-icon-function">
                                @if ($config == 'deleted')
                                    <a href="{{ route('customer.restore', $item->id)}}" title="Khôi phục">
                                        <div class="item edit">
                                            <i class="fa-solid fa-retweet"></i>
                                        </div>
                                    </a>
                                    <form class="form-delete" action="{{ route('customer.forceDelete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete item text-danger delete" title="Xóa"
                                            data-text2="" 
                                            data-text="Bạn không thể khôi phục dữ liệu sau khi xóa!">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('customer.edit', $item->id)}}" title="Chỉnh sửa">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>  
                                    </a>
                                    <form class="form-delete" action="{{ route('customer.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete item text-danger delete" title="Xóa"
                                            data-text2=""
                                            data-text="Bạn có thể khôi phục dữ liệu lại sau khi xóa.">
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
            <h3>Không tìm thấy {{ request('keyword') ? '"'.request('keyword').'"' : ''}}</h3>
        </div>
    @endif
</div>
<nav aria-label="Page navigation example">
    <ul class="pagination d-flex justify-content-center my-3">
        {{ $customers->appends(request()->all())->links() }}
    </ul>
</nav>
