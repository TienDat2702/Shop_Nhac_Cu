@extends('admin.layout.layout')
@section('title', 'Cấp độ thành viên')
@section('main')
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Cấp độ thành viên</h3>

                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('dashboard.index') }}">
                            <div class="text-tiny">Bảng điều khiển</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Cấp độ thành viên</div>
                    </li>
                </ul>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="wg-box">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Cấp độ</th>
                                        <th>Phần trăm giảm</th>
                                        <th>Tổng mua tối đa</th>
                                    </tr>
                                </thead>
                                <form action="{{ route('loyalty.update') }}" method="post">
                                    @csrf
                                <tbody>
                                    @foreach ($loyalty as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->level_name }}</td>
                                            <td><input class="input_loyalty" type="number" value="{{ $item->discount_rate * 100 }}"
                                                    name="{{'discount_rate_' . $item->id }}"> (%)</td>
                                            <td>
                                                <input class="input_loyalty" type="number" value="{{ $item->order_total_price }}"
                                                    name="{{ 'order_total_price_' . $item->id}}"> (VNĐ)
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center"><button style="font-size: 16px" class="btn btn-primary px-5 py-3">Lưu</button></div>
                        </form>
                        </div>
                        
                    </div>
            </div>
        </div>
    </div>

@endsection
