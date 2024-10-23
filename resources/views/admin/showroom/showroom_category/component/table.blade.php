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
            <input class="toggleswitch" name="publish" type="checkbox"
                value="{{ $item->publish }}" data-id="{{ $item->id }}"
                {{ $item->publish == 2 ? 'checked' : '' }}
                {{ $config == 'deleted' ? 'disabled' : '' }} 
                onchange="this.form.submit()"> <!-- Tự động submit form khi checkbox thay đổi -->
            <span class="roundbutton"></span>
        </label>
    </form>
</td>
<td>
    <div class="list-icon-function">
        @if ($config == 'deleted')
            <form class="form-restore" action="{{ route('showroom.restore', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-restore" title="Khôi phục">
                    <i class="fa-solid fa-retweet"></i>
                </button>
            </form>
            <form class="form-delete" action="{{ route('showroom.forceDelete', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete text-danger" title="Xóa">
                    <i class="icon-trash-2"></i>
                </button>
            </form>
        @else
            <a href="{{ route('showroom.edit', $item->id) }}" title="Chỉnh sửa">
                <div class="item edit">
                    <i class="icon-edit-3"></i>
                </div>  
            </a>
            <form class="form-delete" action="{{ route('showroom.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete text-danger" title="Xóa">
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


</div>
