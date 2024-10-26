@extends('admin.layout.layout')

@section('crumb_parent', 'Mã Giảm Giá')
@section('title', 'Danh Sách Mã Giảm Giá')
@section('main')
    <div class="main-content-inner">
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
                        <div class="text-tiny">@yield('crumb_parent')</div>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary mb-20">Thêm Mã Giảm Giá Mới</a>
            <div class="wg-box">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tỷ Lệ Giảm Giá</th>
                            <th>Giá Trị Tối Đa</th>
                            <th>Ngày Bắt Đầu</th>
                            <th>Ngày Kết Thúc</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                            <tr>
                                <td>{{ $discount->code }}</td>
                                <td>{{ $discount->discount_rate }}</td>
                                <td>{{ $discount->max_value }}</td>
                                <td>{{ $discount->start_date }}</td>
                                <td>{{ $discount->end_date }}</td>
                                <td>
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-warning">Chỉnh Sửa</a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
