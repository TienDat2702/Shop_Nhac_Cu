@extends('user.layouts.app')
@section('title', 'Thương hiệu ' . $brand->name)
@section('content')
    <main class="pt-135">

        <section class="shop-main container d-flex pt-4 pt-xl-5">
            <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Trang chủ</a>
                <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">@yield('title')</a>
            </div>
        </section>
        <section class="shop-main container d-flex pt-4 pt-xl-5">
            <h2>{{ $brand->name }}</h2>
            <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                <form method="GET" action="{{ route('brands.index', ['slug' => $brand->slug]) }}">
                    <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" name="sort"
                        onchange="this.form.submit()">
                        <option value="">Sắp xếp theo</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp
                        </option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên: Z-A</option>
                    </select>
                </form>
            </div>
        </section>
        <section class="shop-main container pt-4 pt-xl-5">
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card product-index product-card_style3 mb-3 mb-md-4 mb-xxl-5 pst">
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
                                        <span class="money price text-secondary">{{ number_format($product->price) }}
                                            VNĐ</span>
                                    @else
                                        <span class="money price-old">{{ number_format($product->price) }} VNĐ</span>
                                        <span class="money price text-secondary">{{ number_format($product->price_sale) }}
                                            VNĐ</span>
                                    @endif
                                </div>

                                <div
                                    class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                    <a href="#" data-url="{{ route('cart.add', $product->id) }}"
                                        class="btn-link btn-link_lg me-4 text-uppercase fw-medium add-to-cart"
                                        data-aside="cartDrawer" title="Add To Cart">Thêm Giỏ Hàng</a>
                                    <a href="{{ route('product.detail', $product->slug) }}"
                                        class="btn-link btn-link_lg me-4 text-uppercase fw-medium" title="Quick view">
                                        <span class="">Xem Ngay</span>
                                    </a>
                                    @if (array_key_exists($product->id, $product_favourite))
                                        <!-- Sản phẩm đã yêu thích -->
                                        <form action="{{ route('wishlist.remove', $product_favourite[$product->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="menu-link menu-link_us-s add-to-wishlist">
                                                <i class="fa-solid fa-heart"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Sản phẩm chưa yêu thích -->
                                        <form action="{{ route('wishlist.add', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="menu-link menu-link_us-s add-to-wishlist">
                                                <i class="fa-regular fa-heart"></i> <!-- Trái tim rỗng -->
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap-10 wgp-pagination">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </section>

    </main>
@endsection
