@extends('user.layouts.app')
@section('title','Kết quả tìm kiếm ' . $searchTerm)
@section('content')

<main class="pt-90">
    <section class="shop-main container d-flex pt-4 pt-xl-5">
        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Trang chủ</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Sản phẩm đang tìm kiếm: {{ $searchTerm }}</a>
        </div>
    </section>

    <section class="shop-main container d-flex pt-4 pt-xl-5">
        <h2>Kết quả tìm kiếm cho: {{ $searchTerm }}</h2>
    </section>

    <section class="shop-main container pt-4 pt-xl-5">
        <div class="row">
            @if($products->count())
                @foreach ($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5 pst">
                            @if ($product->price_sale)
                                    <div class="product_sale">
                                        <span>Giảm giá</span>
                                    </div>
                                @endif
                            <div class="pc__img-wrapper">
                                <a href="{{ route('product.detail', $product->slug) }}">
                                    <img loading="lazy" src="{{ asset('uploads/products/product/' . $product->image) }}"
                                        width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                                </a>
                            </div>

                            <div class="pc__info position-relative">
                                <h6 class="pc__title">{{ $product->name }}</h6>
                                <div class="product-card__price d-flex align-items-center">
                                    @if ($product->price_sale == null)
                                        <span class="money price text-secondary">{{ number_format($product->price) }} VNĐ</span>
                                    @else
                                        <span class="money price-old">{{ number_format($product->price) }} VNĐ</span>
                                        <span class="money price text-secondary">{{ number_format($product->price_sale) }} VNĐ</span>
                                    @endif
                                </div>

                                <div class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                    <a href="#" data-url="{{ route('cart.add', $product->id) }}"
                                        class="btn-link btn-link_lg me-4 text-uppercase fw-medium add-to-cart"
                                        data-aside="cartDrawer" title="Add To Cart">Thêm Giỏ Hàng</a>
                                    <a href="{{ route('product.detail', $product->slug) }}"
                                        class="btn-link btn-link_lg me-4 text-uppercase fw-medium" title="Quick view">
                                        <span class="">Xem Ngay</span>
                                    </a>
                                    @if ($product_favourite != '')
                                        @if (array_key_exists($product->id, $product_favourite)) <!-- Sản phẩm đã yêu thích -->
                                            <form action="{{ route('wishlist.remove', $product_favourite[$product->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="menu-link menu-link_us-s add-to-wishlist">
                                                    <i class="fa-solid fa-heart"></i> <!-- Trái tim tô đen -->
                                                </button>
                                            </form>
                                        @else <!-- Sản phẩm chưa yêu thích -->
                                            <form action="{{ route('wishlist.add', $product->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="menu-link menu-link_us-s add-to-wishlist">
                                                    <i class="fa-regular fa-heart"></i> <!-- Trái tim rỗng -->
                                                </button>
                                            </form>
                                        @endif 
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa tìm kiếm của bạn.</p>
            @endif
        </div>
    </section>
</main>
@endsection