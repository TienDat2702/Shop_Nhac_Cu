@extends('user.layouts.app')

@section('content')
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Sản phẩm yêu thích của bạn</h2>
            <div class="row">
                <div class="col-lg-2">
                    <ul class="account-nav">
                        @include('user.layouts.component.sidebarUser')
                    </ul>
                </div>

                <div class="col-lg-10">
                    <div id="notification" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1000; background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
                        <span id="notification-message"></span>
                    </div>
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                            <thead>
    <tr>
        <th class="text-center">Sản phẩm</th>
        <th class="text-center">Giá</th>
        <th class="text-center">Ngày yêu thích</th> <!-- New column for date added -->
        <th class="text-center">Hình ảnh</th>
        <th class="text-center">Hành động</th>
    </tr>
</thead>
<tbody>
    @if($favourites->isEmpty())
        <tr>
            <td colspan="6" class="text-center">Wishlist của bạn trống</td> <!-- Updated colspan -->
        </tr>
    @else
        @foreach($favourites as $favourite)
            <tr>
                <td class="text-center">{{ $favourite->product->name }}</td>
                <td class="text-center">{{ number_format($favourite->product->price) }} VNĐ</td>
                <td class="text-center">{{ $favourite->created_at->format('d/m/Y') }}</td> <!-- Display date added -->
                <td class="text-center">
                    <img src="{{ asset('uploads/products/product/' . $favourite->product->image) }}" alt="{{ $favourite->product->name }}" width="100">
                </td>
                <td class="text-center">
                    <form action="{{ route('wishlist.remove', $favourite->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
