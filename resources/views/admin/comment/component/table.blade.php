<div class="wg-table table-all-product">
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;" class="text-center">STT</th>
                <th class="text-center" style="width: 17%;">Khách hàng</th>
                <th class="text-center" style="width: 28%;">Bình luận</th>
                <th class="text-center" style="width: 6%;">Đánh giá</th>
                <th class="text-center" style="width: 27%;">Sản phẩm</th>
                <th class="text-center" style="width: 12%;">Ngày</th>
                <th class="text-center" style="width: 6%;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @if ($comments->isNotEmpty())  <!-- Kiểm tra xem có sản phẩm nào không -->
                @foreach ($comments as $index => $item)
                    @if ($item->product)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <p><strong>Tên: </strong>{{ $item->customer->name }}</p>
                                <p><strong>phone: </strong>{{ $item->customer->phone }}</p>
                                <p><strong>email: </strong>{{ $item->customer->email }}</p>
                            </td>
                            <td>{{ $item->comment }}</td>
                            <td class="text-center">{{ $item->rating }}/5</td>
                            <td>
                                <a href="{{ route('product.edit', $item->product->slug) }}">
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="{{ asset('uploads/products/product/' . $item->product->image) }}" alt="">
                                            </div>
                                            <div class="col-9">
                                                <strong>{{ $item->product->name }}</strong>
                                                <p>Danh mục: {{  $item->product->productCategory->name ?? '' }}</p>
                                                <p>Thương hiệu: {{  $item->product->brand->name ?? '' }}</p>
                                            </div>
                                        </div>  
                                </a>
                            </td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                            <td>
                                <div class="list-icon-function d-flex justify-content-center">
                                    <form class="form-delete" action="{{ route('comment.delete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete item text-danger delete"
                                            title="Xóa" data-text2=""
                                            data-text="Bạn không thể khôi phục dữ liệu sau khi xóa!">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endif
                    
                @endforeach
               
            @else
                <tr>
                    <td colspan="10" class="text-center">Không có bình luận nào</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
